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
define( 'DB_NAME', 'astrapro' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'R[(Ts[>m,?V4?KD,(1E%x48#ZqC;yXPZ].+BA?!V4 #uU!$o4ddrd]m No2R8vd7' );
define( 'SECURE_AUTH_KEY',  '-Q4&+pCg]J+zK</qaKEO/#7G^v_+ax6 UX&5j]3>iXrLJ[/IACt;OyD@Dntf dWH' );
define( 'LOGGED_IN_KEY',    '%5gBxLts/A5:0-d0K]hKwSVCh+33-x2QXc,k(]J =nAZOBK`M@c2EM26a!oj(2%&' );
define( 'NONCE_KEY',        'Aamb&f$T91G$ah2}mz#?/gAcsS*mXuw+yG;lU zP)$~`pB<8p!-8>=l>a4J=*nM{' );
define( 'AUTH_SALT',        '%rz3_+XK </&5]l;E{jjD78V7WCbc~cRoe4r&.^o)1u=71:[lAorp?GSaN*$-.u|' );
define( 'SECURE_AUTH_SALT', '7O8v>pd(uLd*icOIlG>|0~W1m}zw{vi}9}fn;a-?U X>F$33AArN]6sg31-Sb/Dy' );
define( 'LOGGED_IN_SALT',   '|m].;lL4:L-@BDlWe]2AgvJ@*%U$A/S/%t) Ww(&[=zcV1k0TJG(g {[Ay}.~EG<' );
define( 'NONCE_SALT',       '.d- ]+}$h0aGH&{b<w8Ff2*A6!I+Qjko2@3V>3qS xDe1W,0Y>$U1;t2g(55a,&C' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_astrapro';

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
