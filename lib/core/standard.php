<?php

/**
 * Description of standard
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: standard
 */

function get_client_ip()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset ($_SERVER ['REMOTE_ADDR']) && $_SERVER ['REMOTE_ADDR'] && strcasecmp($_SERVER ['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER ['REMOTE_ADDR'];
    else
        $ip = null;
    return ($ip);
}

function debug($entree, $die = false)
{
    echo '<pre>';
    if (in_array(gettype($entree), array('array', 'object')))
        print_r($entree);
    else
        var_dump($entree);
    echo '</pre>';

    if ($die) exit;
}

function get_infos($ip)
{
    if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {

        if (false !== ($_json = @curl_get_file_contents("http://ip-api.com/json/{$ip}"))) {
            return (json_decode($_json, true)['status'] == 'success') ? serialize($_json) : '';
        } else {
            return '';
        }
    } else {
        return '';
    }
}

function redirect($url, $redirect_type = null)
{
    // debug($url);
    $_url = (preg_match('~^[htp]+s?[:/]+~', $url)) ? $url : WEB_ROOT . $url;

    if (is_numeric($redirect_type)) {
        header("HTTP/1.1 {$redirect_type} Moved Permanently", TRUE, 301);
        header("Location: $_url", TRUE);
    } else
        header("Location: $_url");

    exit();
}

function siteURL()
{
    $protocol = (!empty ($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER ['HTTP_HOST'] . '/';
    return $protocol . $domainName;
}

function recursive_array_search($needle, $haystack)
{
    foreach ($haystack as $key => $value) {
        $current_key = $key;
        if ($needle === $value or (is_array($value) && recursive_array_search($needle, $value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

function crypter($string)
{
    return md5(hash('sha512', $string, false));
}

function cleanString($string)
{
    return filter_var(trim($string), FILTER_SANITIZE_STRING);
}

function cleanArray($data = array())
{
    return filter_var_array($data, FILTER_SANITIZE_STRING);
}

function cleanEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function cleanPost($type, $index)
{
    return filter_input($type, $index);
}

function refaireTel($tel)
{

    $tel = preg_replace('#[-\/\s.]#', '', $tel);

    if (preg_match('#^[+]#', $tel)) {
        $indicatif = substr($tel, 0, 4);
        $start = substr($tel, 4, 3);
        $tel = $tel = str_split(substr($tel, 7, strlen($tel)), 2);;

        return $indicatif . ' ' . $start . ' ' . join(' ', $tel);
    } else {
        $start = substr($tel, 0, 4);
        $tel = $tel = str_split(substr($tel, 4, strlen($tel)), 2);;

        return $start . ' ' . join(' ', $tel);
    }
}

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d [$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}

function is_logged()
{

    $typeCompt = unserialize($_SESSION['type_compt']);

    return ($typeCompt->getTypeCompte() != 'nobody') ? true : false;
}

function is_admin()
{

    $typeCompt = unserialize($_SESSION['type_compt']);

    return ($typeCompt->getTypeCompte() == 'admin') ? true : false;
}


function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function getFormat($type)
{
    $return = '';
    if ($type == 'application/msword')
        $return = 'doc';
    if ($type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
        $return = 'docx';
    if ($type == 'application/rtf')
        $return = 'rtf';
    if ($type == 'application/pdf')
        $return = 'pdf';
    return $return;
}

function make_thumb($src, $dest, $desired_width)
{

    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height * ($desired_width / $width));

    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);
}

function date_format_fr($date)
{
    $d = date('d', strtotime($date));
    $y = date('Y', strtotime($date));
    $h = date('H:i', strtotime($date));
    $semaine = array('dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam');
    $mois = array('jan.', 'fev.', 'mars', 'avr.', 'mai', 'jui', 'juil.', 'aout', 'sept.', 'oct.', 'nov.', 'dec.');

    return $semaine[date('w', strtotime($date))] . ", {$d} " . $mois[date('m', strtotime($date)) - 1] . " {$y} {$h}";
}

function is_ajax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) ? true : false;
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                else unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function curl_get_file_contents($URL)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    curl_close($c);

    if ($contents) return $contents;
    else return FALSE;
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function scan_dir($dir, $ext = false)
{

    $fi = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
    $res = array();
    foreach ($fi as $item) {
        $spl = new SplFileInfo($item);

        if (!$spl->isDir()) {
            $p = get_permission($item);
            $w = (is_writable(dirname($item))) ? '4b9700' : 'bb0f05';

            if (!$ext) {
                array_push($res, array($spl->getFilename(), $spl->getExtension(), date("d-m-Y H:i", $spl->getMTime()), formatSizeUnits($spl->getSize()), $p, $w));
            } else {
                if ($spl->getExtension() == $ext) {
                    array_push($res, array($spl->getFilename(), $spl->getExtension(), date("d-m-Y H:i", $spl->getCTime()), formatSizeUnits($spl->getSize()), $p, $w));
                }
            }
        }
    }
    return $res;
}

function get_permission($path)
{

    $perms = fileperms($path);

    switch ($perms & 0xF000) {
        case 0xC000: // socket
            $info = 's';
            break;
        case 0xA000: // symbolic link
            $info = 'l';
            break;
        case 0x8000: // regular
            $info = 'r';
            break;
        case 0x6000: // block special
            $info = 'b';
            break;
        case 0x4000: // directory
            $info = 'd';
            break;
        case 0x2000: // character special
            $info = 'c';
            break;
        case 0x1000: // FIFO pipe
            $info = 'p';
            break;
        default: // unknown
            $info = 'u';
    }

// Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
        (($perms & 0x0800) ? 's' : 'x') :
        (($perms & 0x0800) ? 'S' : '-'));

// Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
        (($perms & 0x0400) ? 's' : 'x') :
        (($perms & 0x0400) ? 'S' : '-'));

// World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
        (($perms & 0x0200) ? 't' : 'x') :
        (($perms & 0x0200) ? 'T' : '-'));

    return $info;

}

function format_text($word)
{
    return htmlentities($word);
}

function calcule_rate($v5, $v4, $v3, $v2, $v1)
{
    return number_format((5 * $v5 + 4 * $v4 + 3 * $v3 + 2 * $v2 + 1 * $v1) / ($v5 + $v4 + $v3 + $v2 + $v1), 2);
}

function random_password($length = 8)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

function browser()
{
    $os = new OS_BR();
    return $os->getBrowser();
}

function get_os()
{
    $os = new OS_BR();
    return $os->getOS();
}

function object_to_array($object)
{
    if (is_object($object)) {
        return array_map(__FUNCTION__, get_object_vars($object));
    } else if (is_array($object)) {
        return array_map(__FUNCTION__, $object);
    } else {
        return $object;
    }
}

function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text); // replace non letter or digits by -
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text); // transliterate
    $text = preg_replace('~[^-\w]+~', '', $text); // remove unwanted characters
    $text = trim($text, '-'); // trim
    $text = preg_replace('~-+~', '-', $text); // remove duplicate -
    $text = strtolower($text); // lowercase

    return (empty($text)) ? 'n-a' : $text;
}

function encode_mimeheader($string, $charset = "UTF-8", $linefeed = "\r\n")
{
    if (!$charset)
        $charset = mb_internal_encoding();

    $start = "=?$charset?B?";
    $end = "?=";
    $encoded = '';

    /* Each line must have length <= 75, including $start and $end */
    $length = 75 - strlen($start) - strlen($end);
    /* Average multi-byte ratio */
    $ratio = mb_strlen($string, $charset) / strlen($string);
    /* Base64 has a 4:3 ratio */
    $magic = $avglength = floor(3 * $length * $ratio / 4);

    for ($i = 0; $i <= mb_strlen($string, $charset); $i += $magic) {
        $magic = $avglength;
        $offset = 0;
        /* Recalculate magic for each line to be 100% sure */
        do {
            $magic -= $offset;
            $chunk = mb_substr($string, $i, $magic, $charset);
            $chunk = base64_encode($chunk);
            $offset++;
        } while (strlen($chunk) > $length);
        if ($chunk)
            $encoded .= ' ' . $start . $chunk . $end . $linefeed;
    }
    /* Chomp the first space and the last linefeed */
    $encoded = substr($encoded, 1, -strlen($linefeed));

    return $encoded;
}

/**
 * easy image resize function
 * @param  $file - file name to resize
 * @param  $string - The image data, as a string
 * @param  $width - new image width
 * @param  $height - new image height
 * @param  $proportional - keep image proportional, default is no
 * @param  $output - name of the new file (include path if needed)
 * @param  $delete_original - if true the original image will be deleted
 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
 * @param  $quality - enter 1-100 (100 is best quality) default is 100
 * @return boolean|resource
 */
function smart_resize_image($file, $string = null, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false, $quality = 100)
{

    if ($height <= 0 && $width <= 0) return false;
    if ($file === null && $string === null) return false;
    if (!is_file($file)) return false;

    # Setting defaults and meta
    $info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
    $image = '';
    $final_width = 0;
    $final_height = 0;
    list($width_old, $height_old) = $info;
    $cropHeight = $cropWidth = 0;

    # Calculating proportionality
    if ($proportional) {
        if ($width == 0) $factor = $height / $height_old;
        elseif ($height == 0) $factor = $width / $width_old;
        else                    $factor = min($width / $width_old, $height / $height_old);

        $final_width = round($width_old * $factor);
        $final_height = round($height_old * $factor);
    } else {
        $final_width = ($width <= 0) ? $width_old : $width;
        $final_height = ($height <= 0) ? $height_old : $height;
        $widthX = $width_old / $width;
        $heightX = $height_old / $height;

        $x = min($widthX, $heightX);
        $cropWidth = ($width_old - $width * $x) / 2;
        $cropHeight = ($height_old - $height * $x) / 2;
    }

    # Loading image to memory according to type
    switch ($info[2]) {
        case IMAGETYPE_JPEG:
            $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
            break;
        case IMAGETYPE_GIF:
            $file !== null ? $image = imagecreatefromgif($file) : $image = imagecreatefromstring($string);
            break;
        case IMAGETYPE_PNG:
            $file !== null ? $image = imagecreatefrompng($file) : $image = imagecreatefromstring($string);
            break;
        default:
            return false;
    }


    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor($final_width, $final_height);
    if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
        $transparency = imagecolortransparent($image);
        $palletsize = imagecolorstotal($image);

        if ($transparency >= 0 && $transparency < $palletsize) {
            $transparent_color = imagecolorsforindex($image, $transparency);
            $transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
            imagefill($image_resized, 0, 0, $transparency);
            imagecolortransparent($image_resized, $transparency);
        } elseif ($info[2] == IMAGETYPE_PNG) {
            imagealphablending($image_resized, false);
            $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
            imagefill($image_resized, 0, 0, $color);
            imagesavealpha($image_resized, true);
        }
    }
    imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
    imagerotate($image_resized, -90, 0);

    # Taking care of original, if needed
    if ($delete_original) {
        if ($use_linux_commands) exec('rm ' . $file);
        else @unlink($file);
    }

    # Preparing a method of providing result
    switch (strtolower($output)) {
        case 'browser':
            $mime = image_type_to_mime_type($info[2]);
            header("Content-type: $mime");
            $output = NULL;
            break;
        case 'file':
            $output = $file;
            break;
        case 'return':
            return $image_resized;
            break;
        default:
            break;
    }

    # Writing image according to type to the output destination and image quality
    switch ($info[2]) {
        case IMAGETYPE_GIF:
            imagegif($image_resized, $output);
            break;
        case IMAGETYPE_JPEG:
            imagejpeg($image_resized, $output, $quality);
            break;
        case IMAGETYPE_PNG:
            $quality = 9 - (int)((0.9 * $quality) / 10.0);
            imagepng($image_resized, $output, $quality);
            break;
        default:
            return false;
    }

    return true;
}

function uploadFile($file_field = null, $path_image = null, $name_image = null, $check_image = true, $random_name = false)
{

    /**  Config Section */

    $path = $path_image; //Set file upload path, with trailing slash
    $max_size = 1000000;//Set max file size in bytes
    $whitelist_ext = array('jpeg', 'jpg', 'png', 'gif');//Set default file extension whitelist
    $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');//Set default file type whitelist

    /** The Validation */

    $out = array('error' => null);// Create an array to hold any output

    if (!$file_field) $out['error'][] = "Please specify a valid form field name";
    if (!$path) $out['error'][] = "Please specify a valid upload path";
    if (count($out['error']) > 0) return $out;

    //Make sure that there is a file
    if ((!empty($_FILES[$file_field])) && ($_FILES[$file_field]['error'] == 0)) {

        $file_info = pathinfo($_FILES[$file_field]['name']);// Get filename
        $name = $file_info['filename'];
        $ext = $file_info['extension'];

        if (!in_array(strtolower($ext), $whitelist_ext)) $out['error'][] = "Invalid file Extension";//Check file has the right extension
        if (!in_array($_FILES[$file_field]["type"], $whitelist_type)) $out['error'][] = "Invalid file Type";//Check that the file is of the right type
        if ($_FILES[$file_field]["size"] > $max_size) $out['error'][] = "File is too big";//Check that the file is not too big

        //If $check image is set as true
        if ($check_image && !getimagesize($_FILES[$file_field]['tmp_name'])) $out['error'][] = "Uploaded file is not a valid image";

        //Create full filename including path
        if ($random_name) {
            // Generate random filename
            $tmp = str_replace(array('.', ' '), array('', ''), microtime());

            if (!$tmp || $tmp == '') {
                $out['error'][] = "File must have a name";
            }
            $newname = $tmp . '.' . $ext;
        } else
            $newname = $name_image . '.' . $ext;


        if (file_exists($path . $newname)) $out['error'][] = "A file with this name already exists";//Check if file already exists on server

        if (count($out['error']) > 0) return $out;//The file has not correctly validated

        if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $path . $newname)) {
            //Success
            $out['filepath'] = $path;
            $out['filename'] = $newname;
            return $out;
        } else {
            $out['error'][] = "Server Error!";
        }

    } else {
        $out['error'][] = "No file uploaded";
        return $out;
    }
}

function special_char($string)
{

    $string = str_replace('Á', '&Aacute; ', $string);
    $string = str_replace('á', '&aacute; ', $string);
    $string = str_replace('À', '&Agrave; ', $string);
    $string = str_replace('Â', '&Acirc; ', $string);
    $string = str_replace('à', '&agrave;', $string);
    $string = str_replace('Â', '&Acirc;', $string);
    $string = str_replace('â', '&acirc;', $string);
    $string = str_replace('Ä', '&Auml;', $string);
    $string = str_replace('ä', '&auml;', $string);
    $string = str_replace('Ã', '&Atilde;', $string);
    $string = str_replace('ã', '&atilde;', $string);
    $string = str_replace('Å', '&Aring;', $string);
    $string = str_replace('å', '&aring;', $string);
    $string = str_replace('Æ', '&Aelig;', $string);
    $string = str_replace('æ', '&aelig;', $string);
    $string = str_replace('Ç', '&Ccedil;', $string);
    $string = str_replace('ç', '&ccedil;', $string);
    $string = str_replace('Ð', '&Eth;', $string);
    $string = str_replace('ð', '&eth;', $string);
    $string = str_replace('É', '&Eacute;', $string);
    $string = str_replace('é', '&eacute;', $string);
    $string = str_replace('È', '&Egrave;', $string);
    $string = str_replace('è', '&egrave;', $string);
    $string = str_replace('Ê', '&Ecirc;', $string);
    $string = str_replace('ê', '&ecirc;', $string);
    $string = str_replace('Ë', '&Euml;', $string);
    $string = str_replace('ë', '&euml;', $string);
    $string = str_replace('Í', '&Iacute;', $string);
    $string = str_replace('í', '&iacute;', $string);
    $string = str_replace('Ì', '&Igrave;', $string);
    $string = str_replace('ì', '&igrave;', $string);
    $string = str_replace('Î', '&Icirc;', $string);
    $string = str_replace('î', '&icirc;', $string);
    $string = str_replace('Ï', '&Iuml;', $string);
    $string = str_replace('ï', '&iuml;', $string);
    $string = str_replace('Ñ', '&Ntilde;', $string);
    $string = str_replace('ñ', '&ntilde;', $string);
    $string = str_replace('Ó', '&Oacute;', $string);
    $string = str_replace('ó', '&oacute;', $string);
    $string = str_replace('Ò', '&Ograve;', $string);
    $string = str_replace('ò', '&ograve;', $string);
    $string = str_replace('Ô', '&Ocirc;', $string);
    $string = str_replace('ô', '&ocirc;', $string);
    $string = str_replace('Ö', '&Ouml;', $string);
    $string = str_replace('ö', '&ouml;', $string);
    $string = str_replace('Õ', '&Otilde;', $string);
    $string = str_replace('õ', '&otilde;', $string);
    $string = str_replace('Ø', '&Oslash;', $string);
    $string = str_replace('ø', '&oslash;', $string);
    $string = str_replace('ß', '&szlig;', $string);
    $string = str_replace('Þ', '&Thorn;', $string);
    $string = str_replace('þ', '&thorn;', $string);
    $string = str_replace('Ú', '&Uacute;', $string);
    $string = str_replace('ú', '&uacute;', $string);
    $string = str_replace('Ù', '&Ugrave;', $string);
    $string = str_replace('ù', '&ugrave;', $string);
    $string = str_replace('Û', '&Ucirc;', $string);
    $string = str_replace('û', '&ucirc;', $string);
    $string = str_replace('Ü', '&Uuml;', $string);
    $string = str_replace('ü', '&uuml;', $string);
    $string = str_replace('Ý', '&Yacute;', $string);
    $string = str_replace('ý', '&yacute;', $string);
    $string = str_replace('ÿ', '&yuml;', $string);
    $string = str_replace('©', '&copy;', $string);
    $string = str_replace('®', '&reg;', $string);
    $string = str_replace('™', '&trade;', $string);
    $string = str_replace('€', '&euro;', $string);
    $string = str_replace('¢', '&cent;', $string);
    $string = str_replace('£', '&pound;', $string);
    $string = str_replace('—', '&mdash;', $string);
    $string = str_replace('–', '&ndash;', $string);
    $string = str_replace('°', '&deg;', $string);
    $string = str_replace('±', '&plusmn;', $string);
    $string = str_replace('¼', '&frac14;', $string);
    $string = str_replace('½', '&frac12;', $string);
    $string = str_replace('¾', '&frac34;', $string);
    $string = str_replace('×', '&times;', $string);
    $string = str_replace('÷', '&divide;', $string);
    $string = str_replace('α', '&alpha;', $string);
    $string = str_replace('β', '&beta;', $string);
    $string = str_replace('∞', '&infin;', $string);

    return $string;

}

function save_base64_image($base64_image_string, $output_file, $path = "")
{
    $splited = explode(',', substr($base64_image_string, 5), 2);
    $mime = $splited[0];
    $data = $splited[1];

    $mime_split_without_base64 = explode(';', $mime, 2);
    $mime_split = explode('/', $mime_split_without_base64[0], 2);
    if (count($mime_split) == 2) {
        $extension = $mime_split[1];
        if ($extension == 'jpeg') $extension = 'jpg';

        $output_file = $output_file . '.' . $extension;
    }
    file_put_contents($path . $output_file, base64_decode($data));
    return $output_file;
}

function update_string($string, $params)
{
    return str_replace('%SITE_NAME%', $params['SITE_NAME'], $string);
}

function resize_image($file, $w, $h, $crop = FALSE)
{
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width - ($width * abs($r - $w / $h)));
        } else {
            $height = ceil($height - ($height * abs($r - $w / $h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}


function charset_decode_utf_8($string)
{
    /* Only do the slow convert if there are 8-bit characters */
    if (!preg_match("/[\200-\237]/", $string) && !preg_match("/[\241-\377]/", $string))
        return $string;

    // decode three byte unicode characters
    $string = preg_replace_callback("/([\340-\357])([\200-\277])([\200-\277])/",
        create_function('$matches', 'return \'&#\'.((ord($matches[1])-224)*4096+(ord($matches[2])-128)*64+(ord($matches[3])-128)).\';\';'),
        $string);

    // decode two byte unicode characters
    $string = preg_replace_callback("/([\300-\337])([\200-\277])/",
        create_function('$matches', 'return \'&#\'.((ord($matches[1])-192)*64+(ord($matches[2])-128)).\';\';'),
        $string);

    return $string;
}