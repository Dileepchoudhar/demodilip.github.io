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
define( 'DB_NAME', 'wordpress12' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'O7bgQ+wcmWg(xK{?Bm?x~%-Q*;7M}1RLFhdWMGE3KaF<]@^R%L<^Ctb!DQ8zfLdT' );
define( 'SECURE_AUTH_KEY',  '&` f(b_50InNq)tjsl$/>lEwlvtdR;v =Dbn<gGqkJTdj=-UJ|v*wFAEmcWxzVlB' );
define( 'LOGGED_IN_KEY',    'BrW8pU?Q6,Fe/=O;PzNVxG`B$c+2@#1&F$C6#[Zz 0VuX]70}e((v&5u;j.0Y1rH' );
define( 'NONCE_KEY',        'XhFvd|IK@f7_G,|oLZ-$rZb$YtTUBf(zXFmCps(EVgnvJ$B)Dt6n8R+oH<``g 71' );
define( 'AUTH_SALT',        'J@DF`7Ux#Z]@]j.OR$yA+XOOtG(6lu# xSH-H8C`iA!bIzrS4]8RPX@Mn4&x1?-u' );
define( 'SECURE_AUTH_SALT', '?wT{{X2Q8^(3)1:]#m9.nYe/A2e=hTGrN(sRM|EP?vl.$;vDx_14F.+&rS5J3,``' );
define( 'LOGGED_IN_SALT',   '-s@.r$~a9|q#-=#ts{/mkrR<S7Xi# !dx5;wu *)>Kx1rT<s_n?XX(t#!_kw| E-' );
define( 'NONCE_SALT',       'mXzCs/,RoJ)j:D`7R&Il]u Gvr;F%MnbsBuIr_s1,gWS3%i{@:ypn.<gEVR 6I|V' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );

/* Add any custom values between this line and the "stop editing" line. */

// Disable display of errors and warnings
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
