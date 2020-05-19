<?php

/**
 * Description of Router
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: router
 */
class Router
{

    static $list_rout = array(
        SITE_WEB . 'le-the' => SITE_WEB . 'pages/the',
        SITE_WEB . 'the-tea' => SITE_WEB . 'pages/the',

        SITE_WEB . 'accessoires' => SITE_WEB . 'pages/accessoires',
        SITE_WEB . 'accessoires/(.*)?' => SITE_WEB . 'pages/accessoire_detail/',

        SITE_WEB . 'musee-wright' => SITE_WEB . 'pages/musee',
        SITE_WEB . 'wright-museum' => SITE_WEB . 'pages/musee',
        SITE_WEB . 'reservation' => SITE_WEB . 'pages/reservation',
        SITE_WEB . 'booking' => SITE_WEB . 'pages/reservation',
        SITE_WEB . 'merci' => SITE_WEB . 'pages/merci',
        SITE_WEB . 'admin' => SITE_WEB . 'pages/login_index',
        SITE_WEB . 'copyright' => SITE_WEB . 'pages/copyright',
        SITE_WEB . 'privacy-and-policy' => SITE_WEB . 'pages/privacyandpolicy',
        SITE_WEB . 'terms-and-conditions' => SITE_WEB . 'pages/termandconditions',
//        SITE_WEB . 'collection/heritage/(.*)?' => SITE_WEB . 'pages/detail/',
        SITE_WEB . 'our-rooms' => SITE_WEB . 'pages/our_rooms',
        SITE_WEB . 'nos-magasins' => SITE_WEB . 'pages/our_rooms',
        SITE_WEB . 'collections' => SITE_WEB . 'pages/collections',
        SITE_WEB . 'collections' => SITE_WEB . 'pages/collections',

        SITE_WEB . 'collections/heritage' => SITE_WEB . 'pages/collection/heritage',
        SITE_WEB . 'collections/1001-nuits' => SITE_WEB . 'pages/collection/1001-nuits',
        SITE_WEB . 'collections/escapade' => SITE_WEB . 'pages/collection/escapade',

        SITE_WEB . 'collections/heritage/(.*)?' => SITE_WEB . 'pages/detail/',
        SITE_WEB . 'collections/1001-nuits/(.*)?' => SITE_WEB . 'pages/detail/',
        SITE_WEB . 'collections/escapade/(.*)?' => SITE_WEB . 'pages/detail/',
        SITE_WEB . 'all-reservations' => SITE_WEB . 'pages/reservations_statu'

//        SITE_WEB . 'collections/(.*)?' => SITE_WEB . 'pages/collection/'
    );
    static $url = null;

    static function getOriginal()
    {
        $return = null;

        foreach (self::$list_rout as $u => $original) {
//            $_u = str_replace('/', '\/', $u);
//            $_u = str_replace('.', '\.', $_u);

            preg_match("#{$u}#", self::$url, $match);

//            debug($u . ' | ' . self::$url);

            if (!empty($match) && empty($match[1])) $return = array($original, str_replace(SITE_WEB, '', $original));
            elseif (!empty($match) && !empty($match[1])) $return = array($original . $match[1], str_replace(SITE_WEB, '', $original . $match[1]));
        }

//        debug($return);
//        exit;
        return $return;

    }
}