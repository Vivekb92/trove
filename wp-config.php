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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pheunaco_trove' );

/** Database username */
define( 'DB_USER', 'pheunaco_trove' );

/** Database password */
define( 'DB_PASSWORD', 'VMware1!2!' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         ';m/eiYm<d>^O{yw;[$lSS84-`Z8BW/ZJ]mY1q0~FU>w`M@Q2 (<rqN84RRleaMtE' );
define( 'SECURE_AUTH_KEY',  '7WZRjgmzcwAq.T?HY0ZIP#s2gIQ~M`iKieAu1HlDGOsM3Bv[sY+6}gATv!/H`B#,' );
define( 'LOGGED_IN_KEY',    '=9:7W-_8U/,Ei(>bE3xF|Gc<UoDj03TlaFU6Fork9a>K_2}@U;&!t^,Ii<2>Fe>@' );
define( 'NONCE_KEY',        '^Fy/wq/?b {VIp+(%pG(0F{4#9(juwa!<.WZ)<7[w)5zH1?BwP,nHu/Lxsc*5Gwf' );
define( 'AUTH_SALT',        ';MpyUIul!591jX0buh DM)La)3?EaLL.~kxbx6aaaz|o*.ebCmH1DBi/AjTc;bA(' );
define( 'SECURE_AUTH_SALT', 'yK>lVP%y{4!D>Oij<MH$${jGr@d.inLsBF<<2^AD(PA 5.!ZmWkcUxb=qYUIeUxP' );
define( 'LOGGED_IN_SALT',   'U[5L+Jy)HH1*rl[#vF|6B6R>H)1TGK0&{8`.MJJbQc`Aa}<x+JiQ+RJAyR8PN9mG' );
define( 'NONCE_SALT',       '5LH!|f8Yv>V;^SZ84.}>Ce,Br0mJSw[I}ltd}9XQ8DJyS%>Xd{t?GnHLo{jS[+ch' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
