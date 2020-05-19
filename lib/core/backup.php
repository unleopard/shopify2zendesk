<?php
/**
 * Created by Majoch abdessamad.
 * Date: 19/05/2020
 * Mail: majoch.abdessamad@gmail.com
 * Autor: majoch abdessamad
 * File: Backup mysql
 */

class backup
{

    private $memory_limit = "1M";
    private $max_execut_time = 30;
    private $path_backup = null;
    private $path_zip = null;
    private $file_name = null;

    function __construct($path, $to)
    {
        $this->path_backup = $path;
        $this->path_zip = $to;

        $this->file_name = "backup-" . date("d-m-Y H:i:s") . ".zip";

        $this->memory_limit = "1024M";
        $this->max_execut_time = 0;
    }


    function zipData()
    {
        ini_set('max_execution_time', $this->max_execut_time);
        ini_set('memory_limit', $this->memory_limit);

        if (extension_loaded('zip')) {
            if (file_exists($this->path_backup)) {
                $zip = new ZipArchive();
                if ($zip->open($this->path_zip . DS . $this->file_name, ZIPARCHIVE::CREATE)) {
                    $source = realpath($this->path_backup);
                    if (is_dir($source)) {
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                        foreach ($files as $file) {
                            $file = realpath($file);
                            if (is_dir($file)) {
                                $zip->addEmptyDir(str_replace($source . DS, '', $file . DS));
                            } else if (is_file($file)) {
                                $zip->addFromString(str_replace($source . DS, '', $file), file_get_contents($file));
                            }
                        }
                    } else if (is_file($source)) {
                        $zip->addFromString(basename($source), file_get_contents($source));
                    }
                }
                return $zip->close();
            }
        }
        return false;
    }

}