<?php
/**
 * phpMyAdmin configuration for Melbourne Print Hub Production
 * Configured for Heroku JawsDB MySQL
 */

declare(strict_types=1);

/**
 * This is needed for cookie based authentication to encrypt the cookie.
 * Needs to be a 32-bytes long string of random bytes. See FAQ 2.10.
 */
$cfg['blowfish_secret'] = 'melbourneprinthub2025secretkey32bytes!';

/**
 * Servers configuration
 */
$i = 0;

/**
 * First server - Production JawsDB MySQL
 */
$i++;

/* Authentication type */
$cfg['Servers'][$i]['auth_type'] = 'config';

/* Server parameters - Using JawsDB URL from Heroku */
$cfg['Servers'][$i]['host'] = 'e11wl4mksauxgu1w.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$cfg['Servers'][$i]['port'] = '3306';
$cfg['Servers'][$i]['user'] = 'vetdn5o1nme97t9h';
$cfg['Servers'][$i]['password'] = 'jrm36gzr1gxqifkr';
$cfg['Servers'][$i]['compress'] = false;
$cfg['Servers'][$i]['AllowNoPassword'] = false;

/* Database name */
$cfg['Servers'][$i]['db'] = 'pial0xlk7wore30t';

/* Connection timeout */
$cfg['Servers'][$i]['connect_timeout'] = 5;

/* SSL configuration for secure connection */
$cfg['Servers'][$i]['ssl'] = true;
$cfg['Servers'][$i]['ssl_verify'] = false;

/**
 * Security settings for production
 */
$cfg['LoginCookieValidity'] = 1440; // 24 hours
$cfg['LoginCookieStore'] = 0;
$cfg['LoginCookieDeleteAll'] = true;

/**
 * Directories for saving/loading files from server
 */
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';

/**
 * Display settings
 */
$cfg['MaxRows'] = 50;
$cfg['ShowAll'] = true;
$cfg['ProtectBinary'] = 'blob';

/**
 * Default language
 */
$cfg['DefaultLang'] = 'en';

/**
 * Query history settings
 */
$cfg['QueryHistoryDB'] = false;
$cfg['QueryHistoryMax'] = 25;

/**
 * Error reporting
 */
$cfg['SendErrorReports'] = 'never';

/**
 * Security - Disable some features in production
 */
$cfg['AllowUserDropDatabase'] = false;
$cfg['Confirm'] = true;
$cfg['LoginCookieValidity'] = 1440;

/**
 * End of servers configuration
 */
