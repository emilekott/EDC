<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'edc');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'BVnmf]?*g+Ub4M]c+QE,JI$&Pt07GNd]7Em R^]W4lpP[js+jdg&b>v<OpR>Rnj:');
define('SECURE_AUTH_KEY',  '`x|YsbKI3Y >jID)RmhP@bS=5vs,r=|BB206Zq+YU1Vk;sbx|B]st8D_Z:kN/y6^');
define('LOGGED_IN_KEY',    'C{<{F(mi=b N?ocKG67e?pTvcOjw-`g#CjD.!A)|>[u9,k9!!/:L2[na*]cE7~%*');
define('NONCE_KEY',        'qJ|,]t_eZ1Sy%$!4Z^k87CmNXt#d]qkfj9--|GkTt2TiGNC-hs=0plP`K}^:;~u-');
define('AUTH_SALT',        '3,Vn=B-Xbu}4}5Q&4:9i9E]]*7K=-Y51]O|+W424Y;Okx[%,`FW$wU_L:Y|Mza&S');
define('SECURE_AUTH_SALT', '(b-Bme_EFs0u@ATKooetIsb-e,N=P-#5?z^I!alJ5/e0d&[zh8eV|?:eV=Cb`|uh');
define('LOGGED_IN_SALT',   'zt9C*eQQfL|2x-aa$/@j36E)iiDht!CVbFL-!qM@6$s7+jJ@.Y`n$9{B]SIVZ<nA');
define('NONCE_SALT',       't((cd:d6>YA<F/lT-tH.d+;a(D E7fHt|=Pg+Xg91S}(lk)us^y4Nmb]8p*o<bs6');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
