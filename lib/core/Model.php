<?php

/**
 * Description of Model
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: Model
 */

class Model
{

    var $table;
    var $class;
    protected $requette = null;
    protected $pdo;
    protected $last_inserted_id;
    protected $getters = array();
    protected $setters = array();
    protected $err = null;

    /**
     * The extension allowed.
     *
     * @access private
     * @var array $_aZipExt
     */
    private $_aZipExt = array('gz' => 'gzip', 'bz2' => 'bzip2');

    function __construct()
    {
        if (empty(HOST) || empty(USER) || empty(NAME)) die ('<h3>Erreur DB</h3> fichier de configuration n\'est pas configur&eacute;');

        try {
            $this->pdo = new PDO("mysql:host=" . HOST . ";dbname=" . NAME, USER, PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $Exception) {
            die ('<h3>Erreur DB</h3> ' . $Exception->getMessage());
        }
    }

    protected function getGettersObject($obj)
    {
        $gtrs = array();

        foreach (get_class_methods($obj) as $function) {
            if (startsWith($function, 'get')) array_push($gtrs, $function);
        }
        return $gtrs;
    }

    protected function setTable($table)
    {
        $this->table = $table;
    }

    protected function setClass($cls)
    {
        $this->class = $cls;
        $$cls = new $this->class ();
        $this->table = $$cls->table;

        $this->getters = array();
        $this->setters = array();

        foreach (get_class_methods($$cls) as $function) {
            if (startsWith($function, 'get')) array_push($this->getters, $function);
            elseif (startsWith($function, 'set')) array_push($this->setters, $function);
        }
    }

    protected function getRequette()
    {
        return $this->requette;
    }

    /**
     * @return null
     */
    protected function getErr()
    {
        return $this->err;
    }

    protected function selectAll($order = 1)
    {
        $req = "SELECT * FROM {$this->table} order by $order";
        $res = $this->pdo->query($req);
        $arr_res = array();
        foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $ob = new $this->class ();
            foreach ($ob->metadata as $key => $value) {
                $s = $key;

                if (!empty($this->setters)) {
                    $grep = preg_grep("/set$key/i", $this->setters);

                    if (count($grep) == 1) {
                        foreach ($grep as $g) $s = $g;
                    } else $s = "set" . str_replace('_', '', $s);
                }

                if (is_null($ob->foreignKey) || !in_array($key, array_keys($ob->foreignKey)))
                    $ob->$s($row[$value]);
                else
                    $ob->$s(new $ob->foreignKey [$key]($row[$value]));
            }
            array_push($arr_res, $ob);
        }
        return $arr_res;
    }

    protected function selectGroupBy($condition = null, $group_by = null, $order_by = 1)
    {
        $_group_by = (is_null($group_by)) ? "" : "group by {$order_by}";
        $_condition = (is_null($condition)) ? "" : "where {$condition}";

        $this->requette = "SELECT * FROM {$this->table} {$_condition} {$_group_by} order by {$order_by};";
        $res = $this->pdo->query($this->requette);
        $arr_res = array();
        foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $ob = new $this->class ();
            foreach ($ob->metadata as $key => $value) {
                $s = $key;

                if (!empty($this->setters)) {
                    $grep = preg_grep("/set$key/i", $this->setters);

                    if (count($grep) == 1) {
                        foreach ($grep as $g) $s = $g;
                    } else $s = "set" . str_replace('_', '', $s);
                }

                if (is_null($ob->foreignKey) || !in_array($key, array_keys($ob->foreignKey)))
                    $ob->$s($row[$value]);
                else
                    $ob->$s(new $ob->foreignKey [$key]($row[$value]));
            }
            array_push($arr_res, $ob);
        }
        return $arr_res;
    }

    protected function selectLimit($order = null, $limit = null, $limit2 = null)
    {
        $_limit = (is_null($limit) ? '' : "limit {$limit}");
        $_limit .= (is_null($limit2) ? '' : ",{$limit2}");
        $_order = (!is_null($order)) ? 'ORDER BY ' . $order : 'ORDER BY 1 ASC';

        $this->requette = "SELECT * FROM {$this->table} {$_order} {$_limit};";

        $res = $this->pdo->query($this->requette);
        $arr_res = array();
        foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $ob = new $this->class ();
            foreach ($ob->metadata as $key => $value) {
                $s = (empty($this->setters)) ? $key : "set$key";

                if (!in_array($s, $this->setters))
                    $s = str_replace('_', '', $s);

                if (is_null($ob->foreignKey) || !in_array($key, array_keys($ob->foreignKey)))
                    $ob->$s($row[$value]);
                else
                    $ob->$s(new $ob->foreignKey [$key]($row[$value]));
            }
            array_push($arr_res, $ob);
        }
        return $arr_res;
    }

    protected function selectByCondition($condition, $fields = "*", $order = null, $limit = null, $limit2 = null)
    {
        $_limit = (is_null($limit) ? '' : "limit {$limit}");
        $_limit .= (!is_null($limit2) && !empty($_limit)) ? ",{$limit2}" : '';
        $_order = (!is_null($order)) ? 'ORDER BY ' . $order : 'ORDER BY 1 ASC';


        if (preg_match('#count|avg|min|max|sum[\s]*[(]#i', $fields)) {
            try {
                $_field = preg_replace('#count[\s]*[(]#', 'count(', $fields);
                $this->requette = "SELECT {$_field} FROM {$this->table} WHERE {$condition} {$_order} {$_limit};";

                $res = $this->pdo->query($this->requette);

                return $res->fetchAll(PDO::FETCH_NUM)[0][0];
            } catch (Exception $ex) {
                return false;
            }

        } else {
            try {
                $this->requette = "SELECT {$fields} FROM {$this->table} WHERE {$condition} {$_order} {$_limit}";

                $res = $this->pdo->query($this->requette);
                $arr_res = array();
                foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $ob = new $this->class ();
                    foreach ($ob->metadata as $key => $value) {
                        $s = $key;

                        if (!empty($this->setters)) {
                            $grep = preg_grep("/set$key/i", $this->setters);

                            if (count($grep) == 1) {
                                foreach ($grep as $g) $s = $g;
                            } else $s = "set" . str_replace('_', '', $s);
                        }

                        if (!isset($row[$value]))
                            continue;
                        if (is_null($ob->foreignKey) || !in_array($key, array_keys($ob->foreignKey)))
                            $ob->$s($row[$value]);
                        else
                            $ob->$s(new $ob->foreignKey [$key]($row[$value]));
                    }
                    array_push($arr_res, $ob);
                }

                // debug($arr_res);
                return $arr_res;
            } catch (Exception $ex) {
                return false;
            }
        }
    }

    protected function dump($sDest, $sZip = 'gz')
    {
        $bZip = (array_key_exists($sZip, $this->_aZipExt)) ? true : false;
        $sExt = ($bZip) ? '.' . $sZip : '';
        $sFileName = NAME . '-database-update.' . date('d-m-Y') . '.sql' . $sExt;
        $sOptions = ($bZip) ? ' | ' . $this->_aZipExt[$sZip] : '';
        $this->_sCmd = 'mysqldump -h' . HOST . ' -u' . USER . ' -p' . PASS . ' ' . NAME . $sOptions . ' > ' . $sDest . $sFileName;
        return true;
    }

    protected function insert($object)
    {
        if (gettype($object) === "object") {
            $pdo_statment = new PDOStatement();
            try {

                $this->requette = "INSERT INTO `{$object->table}` (`" . join("`, `", $object->metadata) . "`) VALUES (:" . join(", :", $object->metadata) . ");";

                $pdo_statment = $this->pdo->prepare($this->requette);
                $params = array();
                foreach ($object->metadata as $k => $v) {
                    $r = $k;

                    if (!empty($this->getters)) {
                        $grep = preg_grep("/get$k/i", $this->getters);

                        if (count($grep) == 1) {
                            foreach ($grep as $g) $r = $g;
                        } else $r = "get" . str_replace('_', '', $r);
                    }

                    if (gettype($object->$r()) != "object") {
                        $params[":$v"] = ($k != $object->primaryKey) ? $object->$r() : "null";
                    } else {
                        $g = "get" . $object->$r()->primaryKey;
                        $g_getters = $this->getGettersObject($object->$r());

                        if (!empty($g_getters)) {
                            $greps = preg_grep("/$g/i", $g_getters);

                            if (count($greps) == 1) {
                                foreach ($greps as $grep) $pk = $grep;
                            } else $g = str_replace('_', '', $g);
                        }
                        $params[":$v"] = $object->$r()->$g();
                    }
                }

                $stmt = $pdo_statment->execute($params);

                $this->last_inserted_id = $this->pdo->lastInsertId();
                if ($stmt == false) {
                    return null;
                } else
                    return $stmt;
            } catch (Exception $ex) {

                return null;
            }
        } else {
            return null;
        }
    }

    function get_last_inserted_id()
    {
        return $this->last_inserted_id;
    }

    protected function update($object)
    {
        $fields = "";
        $data = array();

        foreach ($object->metadata as $k => $f) {
            if ($k != $object->primaryKey) {
                $r = $k;

                if (!empty($this->getters)) {
                    $grep = preg_grep("/get$k/i", $this->getters);

                    if (count($grep) == 1) {
                        foreach ($grep as $g) $r = $g;
                    } else $r = "get" . str_replace('_', '', $r);
                }

                if (gettype($object->$r()) != "object") {
                    if (!is_null($object->$r())) {
                        $fields .= "`$f` = ?, ";
                        $data[] = $object->$r();
                    }
                } else {
                    $g = "get" . $object->$r()->primaryKey;
                    $g_getters = $this->getGettersObject($object->$r());

                    if (!empty($g_getters)) {
                        $greps = preg_grep("/$g/i", $g_getters);

                        if (count($greps) == 1) {
                            foreach ($greps as $grep) $pk = $grep;
                        } else $g = str_replace('_', '', $g);
                    }

                    $fields .= "`$f` = ?, ";
                    $data[] = $object->$r()->$g();
                }
            }
        }

        $pk = $object->primaryKey;

        if (!empty($this->getters)) {
            $grep = preg_grep("/get$pk/i", $this->getters);

            if (count($grep) == 1) {
                foreach ($grep as $g) $pk = $g;
            } else $pk = "get" . str_replace('_', '', $pk);
        }

        $data[] = $object->$pk();
        $this->requette = "UPDATE `{$object->table}` SET " . substr($fields, 0, -2) . " WHERE " . $object->metadata[$object->primaryKey] . " = ?;";

        try {
            $pdo_statment = $this->pdo->prepare($this->requette);
            return $pdo_statment->execute($data);

        } catch (Exception $ex) {
            $this->err = $ex->getMessage();
            return false;
        }
    }

    protected function increment($object, $field, $nbr)
    {
        $pk = $object->primaryKey;

        if (!empty($this->getters)) {
            $grep = preg_grep("/get$pk/i", $this->getters);

            if (count($grep) == 1) {
                foreach ($grep as $g) $pk = $g;
            } else $pk = "get" . str_replace('_', '', $pk);
        }

        $data[] = $object->$pk();
        $this->requette = "UPDATE `{$object->table}` SET {$field} = {$field} + {$nbr} WHERE " . $object->metadata[$object->primaryKey] . " = ?;";
        try {

            $pdo_statment = new PDOStatement();
            $pdo_statment = $this->pdo->prepare($this->requette);
            return $pdo_statment->execute($data);
        } catch (Exception $ex) {
            debug($ex->getMessage());
            //return false;
        }
    }

    protected function havePermision($controller, $action)
    {
        $typ_compt = unserialize($_SESSION['type_compt']);

        return $this->prepared(array(
            'fields' => 'count(*) as nombre, page',
            'tables' => array('modules', 'actions', 'type_comptes', 'permissions'),
            'condition' => 'modules.id_module = actions.module and actions.id_action = permissions.`action` and permissions.type_compte = type_comptes.id_type_compte AND modules.module = ? AND actions.`action` = ? AND type_comptes.id_type_compte = ? AND active = ?',
            'params' => array($controller, $action, $typ_compt->getIdTypeCompte(), 'Y')
        ))[0];
    }

    protected function prepared($data = array())
    {
        $condition = "1=1";
        $fields = "*";
        $limit = "";
        $order = "ORDER BY 1 DESC";
        $groupBy = "";
        $tables = $this->table;
        if (isset($data['condition']))
            $condition = $data['condition'];
        if (isset($data['fields'])) {
            if (is_array($data['fields']))
                $fields = join(', ', $data['fields']);
            if (is_string($data['fields']))
                $fields = $data['fields'];
        }
        if (isset($data['limit']))
            $limit = $data['limit'];
        if (isset($data['groupBy'])) {
            $groupBy = " GROUP BY " . join(", ", $data['groupBy']);
            $order = " ORDER BY 1 DESC ";
        } elseif (isset($data['order']))
            $order = " ORDER BY {$data['order']}";

        if (isset($data['tables'])) {
            $tables = join(", ", $data['tables']);
        }

        $sql = "SELECT $fields FROM $tables WHERE $condition $groupBy $order $limit";
        //debug($sql);

        try {
            $result = $this->pdo->prepare($sql);
            $tbl = array();
            // debug($data['params']);
            if (isset($data['params']) && !empty($data['params']))
                $result->execute($data['params']);
            else
                $result->execute();
            $tbl = array();
            foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $value)
                array_push($tbl, $value);

            return $tbl;
        } catch (Exception $ex) {
            return false;
        }
    }

    protected function join($arr = array())
    {

        $fields = '*';
        if (isset($arr['fields']) && is_array($arr['fields'])) $fields = implode(', ', $arr['fields']);
        if (isset($arr['fields']) && is_string($arr['fields'])) $fields = $arr['fields'];

        $condition = (isset($arr['condition'])) ? $arr['condition'] : "1=1";
        $order = (isset($arr['order'])) ? $arr['order'] : "1";
        $join = "";
        foreach ($arr['join'] as $j)
            $join .= " {$j['type']} join {$j['table']} ON {$j['on']} ";

        $group_by = (isset($arr['group_by']) && gettype($arr['group_by']) == 'array') ? " GROUP BY " . implode(', ', $arr['group_by']) : '';
        $limit = (isset($arr['limit']) && !empty($arr['limit'])) ? "LIMIT " . $arr['limit'] : '';

        $this->requette = "SELECT {$fields} FROM " . implode(', ', $arr['tables']) . " $join WHERE {$condition} {$group_by} ORDER BY {$order} {$limit};";

        try {
            $result = $this->pdo->prepare($this->requette);

            if (isset($arr['params']) && !empty($arr['params']))
                $result->execute($arr['params']);
            else
                $result->execute();
            $tbl = array();
            foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $value)
                array_push($tbl, $value);



            return $tbl;
        } catch (Exception $ex) {
            return false;
        }
    }

    protected function query($sql, $params = null)
    {
        try {
            $result = $this->pdo->prepare($sql);
            $tbl = array();
            if (isset($arr['params']) && !empty($arr['params']))
                $result->execute($arr['params']);
            else
                $result->execute();
            $tbl = array();
            foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $value)
                array_push($tbl, $value);

            return $tbl;
        } catch (Exception $ex) {
            return false;
        }
    }

    protected function delete($object)
    {
        try {

            $pk = $object->primaryKey;

            if (!empty($this->getters)) {
                $grep = preg_grep("/get$pk/i", $this->getters);

                if (count($grep) == 1) {
                    foreach ($grep as $g) $pk = $g;
                } else $pk = "get" . str_replace('_', '', $pk);
            }

            $primary_key = $object->metadata[$object->primaryKey];

            $data [] = $object->$pk();

            $sql = "DELETE FROM $this->table where $primary_key =?;";

            $result = $this->pdo->prepare($sql);

            return $result->execute($data);
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    protected function deleteByCondition($condition = null, $data = array())
    {
        try {

            $sql = "DELETE FROM $this->table where {$condition};";
            $result = $this->pdo->prepare($sql);

            return $result->execute($data);
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            return null;
        }
    }

    protected function call_proc($stat_name, $params = array(), $return = false)
    {
        try {
            $_params = '';
            $_ret = ($return) ? $params[count($params) - 1] : '';

            foreach ($params as $p)
                $_params .= '?, ';

            $_params = (!empty($params)) ? rtrim(rtrim($_params), ',') : '';

            $stmt = $this->pdo->prepare("call {$stat_name}({$_params})");
            $stmt->execute($params);

            if ($return)
                return $this->pdo->query("select {$_ret} as `return`")->fetch(PDO::FETCH_ASSOC)['return'];
            else
                return true;
        } catch (Exception $ex) {
            return null;
        }
    }
}

?>