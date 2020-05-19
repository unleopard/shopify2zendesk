<?php

/**
 * Created by Majoch abdessamad.
 * Date: 19/05/2020
 * Mail: majoch.abdessamad@gmail.com
 * Autor: majoch abdessamad
 * File: Cron
 */

$time = time();
set_time_limit(0);
ini_set('memory_limit', '128M');

file_get_contents(str_replace( $_SERVER['REDIRECT_SCRIPT_URL'], '/', $_SERVER['REDIRECT_SCRIPT_URI'] ));
die(json_encode(['success' => true, 'start'=> date('d-m-Y H:i:s', $time), 'end' => date('d-m-Y H:i:s'), 'diff' => time()-$time.' s' ]));
?>