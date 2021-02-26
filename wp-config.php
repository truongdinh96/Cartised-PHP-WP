<?php

/**

 * The base configuration for WordPress

 *

 * The wp-config.php creation script uses this file during the

 * installation. You don't have to use the web site, you can

 * copy this file to "wp-config.php" and fill in the values.

 *

 * This file contains the following configurations:

 *

 * * MySQL settings

 * * Secret keys

 * * Database table prefix

 * * ABSPATH

 *

 * @link https://wordpress.org/support/article/editing-wp-config-php/

 *

 * @package WordPress

 */


// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', 'wordpress' );


/** MySQL database username */

define( 'DB_USER', 'root' );


/** MySQL database password */

define( 'DB_PASSWORD', '' );


/** MySQL hostname */

define( 'DB_HOST', 'localhost' );


/** Database Charset to use in creating database tables. */

define( 'DB_CHARSET', 'utf8mb4' );


/** The Database Collate type. Don't change this if in doubt. */

define( 'DB_COLLATE', '' );


/**#@+

 * Authentication Unique Keys and Salts.

 *

 * Change these to different unique phrases!

 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}

 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define( 'AUTH_KEY',         'p- G4<YkA%dcMN}5yu^]qEIFbb&GK[#_{dn1If,WMLcLxtqBwC9OjI^M?P3<o ,d' );

define( 'SECURE_AUTH_KEY',  '9uYn?^t6Qj@$9bil6k(-}&p|D1e@!Lx21zZEo%ZPu?tV^oo)%l*i=8P<dC5oO3!{' );

define( 'LOGGED_IN_KEY',    'C;k.(lfb8tA5#8Yt<I/|xMnQf[(U^3i:7]7BUiO: tyRip?wf!t{ylNpO.7kb]TP' );

define( 'NONCE_KEY',        'k2l#aY@&a;CU|S3:cn85ihFd_zLh?-r`~HhEp+QxaS|3IWV*My+kpDzt sp.A^-0' );

define( 'AUTH_SALT',        'G8WQ} t!9XP qNLkMZ}{6G0pGcux:aKzABH!AzM;O@+}HfD6sDsBIjOR;E6kErK2' );

define( 'SECURE_AUTH_SALT', '88/AflQGihaD_(Sz{n<zy+At*Tgw 6`yh]FQr;l$Rt@Ich^B ?XWPL }6*]P2_cP' );

define( 'LOGGED_IN_SALT',   'w)/% 1u$Lum6)^O+as6kB[#>dd1}p2MK99L(;[?s55NO)C#UlE!vetba Cn<%UD0' );

define( 'NONCE_SALT',       'IX&MvKCMQ_8>CG#5?V1yN!JpYx%9K -nLe1;=fvrAr!`f11Kr94yp+G49=#wHr}{' );


/**#@-*/


/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'ct_';


/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 *

 * For information on other constants that can be used for debugging,

 * visit the documentation.

 *

 * @link https://wordpress.org/support/article/debugging-in-wordpress/

 */

ini_set('display_errors','Off');

ini_set('error_reporting', E_ALL );

define('WP_DEBUG', false);

//define('WP_DEBUG_DISPLAY', false);


/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

