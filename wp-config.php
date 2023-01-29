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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'KinGfishER360#$' );

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
define( 'AUTH_KEY',         '>; h-o;mU*`)L_v.}7#M=2%6E*C~&j+GtxkPVSDw`m(,U(S@Zv6_%~{/D,)ZHD:x' );
define( 'SECURE_AUTH_KEY',  'n?h@OmmHi2PzQ(Z#aCBjl3pbSN:@H~w<-2%p4z$,Zpz~e58`|-Ps$)A6j<Hdp$/0' );
define( 'LOGGED_IN_KEY',    '8.aI^UMd0,M~zF3OkUOXp9jwa,nNn)NDfm@01yz`EPlYI+ckAq5[4PDG+9>]O[F!' );
define( 'NONCE_KEY',        '7Ix( $F~8`6.}o|A:`[mXEMWwnpoPT+E|#sTzXYp{{)}B&^6A /SUzq1ZGs~A@`&' );
define( 'AUTH_SALT',        'sGx(E=VGuT|Ped?3M-6wFe?[@dIjtLIZ=5/,+@#Sril&mw5TaT_Wjj=>r&/WKUg;' );
define( 'SECURE_AUTH_SALT', 'Lq.;(Y-(zqa|r :UkHnJZ(nlIU{$1SU*&>RQZ#$SFM5O%0OP4)[^Eb!zD:^n,k$g' );
define( 'LOGGED_IN_SALT',   'jUW;#sMYNlQ,+[?!lx!n]C|)+HgbUj]0RGT>nHUxel{=imhLHFnOx4tB6Q?=xs*b' );
define( 'NONCE_SALT',       ';4A>(M|}CfZXoVK+u {k=ePJ;%xL%}gX wBB}nRWB8%-fAa5wY0~rV`]8~!B~@%I' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
