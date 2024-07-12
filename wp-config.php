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
define( 'DB_NAME', 'lms' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'F}[ND<E@nj$Q(2(hct`rW|At&A!MFDKC!gSnxbVUc5G{y_1=~e=WacD{kJdr(I;a' );
define( 'SECURE_AUTH_KEY',  'N2V[BuCo,ibb?zX[9Yf-,xA 5]s3=e%s9xKqB7wn|EQ |U^yemx,B.:(%?Kr Q^M' );
define( 'LOGGED_IN_KEY',    ';{jh&`EB<j2*y+u^{/>+F?0GP~-V|cr=t*<c_c/V^WGR,TN!s.yoJ~6z-{{}7z]1' );
define( 'NONCE_KEY',        'IMm>fP%0/fyfhzDBK!X)|Rn`cP_:QsppTG&j9&b, AJR}!`qT}wY>q_A`7(1]F|%' );
define( 'AUTH_SALT',        'eE$<Vl,6<]4s_5bI6u!BE{wxHO_!xbVFQelCgAmc0T;=Hd_gI6w)}tR|<`^UZ5`7' );
define( 'SECURE_AUTH_SALT', 'xOEEG]Rl 8_<jFyaCy{XIIRgrBf0Nx8_)@5_m.Nm}W{*.PuSZ8PM~{AAVPp90J^h' );
define( 'LOGGED_IN_SALT',   'BXn]yXY4u?WMZzR%q l>_=vE#Gt7?l`JK lM[mF:brGEPm:[/lz}vP HOA|G?CEH' );
define( 'NONCE_SALT',       'N=XbGP=A,L-kCV4aRp^D,y;xR|1b=^VC>, B[[jpWk%Mb@M_tmIwtrx^2pbD%I/u' );

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
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
