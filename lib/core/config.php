<?php
/**
 * Created by Majoch abdessamad.
 * Date: 19/05/2020
 * Mail: majoch.abdessamad@gmail.com
 * Autor: majoch abdessamad
 * File: configuration
 */

/**
 * Default controller and action
 */
define("CONTROLLER_DEFAULT", "accueilController");
define("CONTROLLER_ADMIN_DEFAULT", "loginController");
define("ACTION_DEFAULT", "index");
define('CONTROLLER_SUF', 'Controller');

/**
 * default controller erreur
 */
define("ERREUR_CONTROLLER", "erreur");
define("PAGE_NOT_FOUND", "page_not_found");
define("ACTION_NOT_FOUND", "action_not_found");
define("PERMISSION", "permission");
define("SYSTEM_ERREUR", "system_erreur");
define("ADMIN_PAGE_NOT_FOUND", "admin_page_not_found");
define("ADMIN_ACTION_NOT_FOUND", "admin_action_not_found");
define("ADMIN_PERMISSION", "admin_permission");


/**
 * DB configuration
 */
define("HOST", "");
define("NAME", "");
define("USER", "");
define("PASS", "");
define("SITE_WEB", "");

/**
 *  GOOGLE ANALYTICS "oauth2"
 */
define('CLIENT_ID', '');
define('CLIENT_SECRET', '');
define('REDIRECT', ''); // redirect page
define('ACCOUNT_ID', ''); // like ga:xxxxxxxxx


/**
 *  SMTP configuration
 */
define("SMTP_DEBUG", 2);
define("SMTP_HOST", '');
define("SMTP_PORT", 587);
define("SMTP_USER", '');
define("SMTP_PASS", '');
define("SMTP_SENDER_NAME", "");



/**
 * Echanges entre shopify et zendesk
 */
define('SHOPIFY_URL_ORDERS', 'https://xxx.myshopify.com/admin/api/2020-04/orders.json');
define('SHOPIFY_USER', '');
define('SHOPIFY_PASS', '');

define('ZEN_URL_NEW_USER', 'https://xxx.zendesk.com/api/v2/users.json');
define('ZEN_URL_FIND_USER', 'https://xxx.zendesk.com/api/v2/users/search.json');
define('ZEN_URL_CREATE_TIKET', 'https://xxx.zendesk.com/api/v2/tickets.json');
define('ZEN_USER', '');
define('ZEN_TOKEN', '');
define('HEADER', 'Content-Type: application/json');