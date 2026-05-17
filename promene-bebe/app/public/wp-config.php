<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'Uu8/dQ/x4xw{P_*L8!Ms?H(&FR}`RiKXV/C#3.N(h>!e#p$A6cU4w/hL[&gL*Qa]' );
define( 'SECURE_AUTH_KEY',   'N+CM.!o%$YOo>3K(4T_Da#Rd`o3dZS6(j.a)rXCCbZgAERA$8R`;ND?44hAD3v{K' );
define( 'LOGGED_IN_KEY',     '9;:Y=st{,t/p.+Jxvaxeg3!gpvwGaxjeSt6.V6{a6j6s&944R`ja$QcQKM#:Ia];' );
define( 'NONCE_KEY',         '`|&65o}9t+5Tq(E&w1}N(^DH5q>LkZMb~(]?D<uSAab+0X^ydjnSI,V3bz<ZlN*C' );
define( 'AUTH_SALT',         'ECT#.^L2j a% [N:oB 91K3^d:-W<95fU/l=z3E_F?bmr?Ly>F[ER79)Yho*OgyL' );
define( 'SECURE_AUTH_SALT',  'W{{kK>!~3(1f3d;Zo6/B7?!-JV}t>=<$Z?cW3Q/THNG#1fxqT*~[;3Zf{Rp2)G`!' );
define( 'LOGGED_IN_SALT',    'SJ*3]rStGW|O@Iy{wo_(8L:xR>tIhT,N@Qa4y7PYIY>^!IOBe{fi()0wyH>?_CIl' );
define( 'NONCE_SALT',        'V#w~&SIeC&WDf@h8(ACl>r1[pTU-O+s6|e6$hjlv-<a!bT|?bla Z.n~CF1>dpcZ' );
define( 'WP_CACHE_KEY_SALT', ':%}TEiJL^|g[?AO&?Hrt>|,B$B~ZH[WPi:[x=dB;O<2109;z02Mis-S$F$<}mbP{' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
