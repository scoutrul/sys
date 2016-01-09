<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'scoutrfe_wp1');

/** MySQL database username */
define('DB_USER', 'scoutrfe_wp1');

/** MySQL database password */
define('DB_PASSWORD', '54Wx2EQwz');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '[j]#bn9s@lJC6gO6cx)H#ogN{$+|~=s-Vu:c7jIi6Mu;JHg$nic-/7LQbE6vzAf1');
define('SECURE_AUTH_KEY',  'AwpuY3V1|(biw~YDP&26PAMn;]r rroTf@N]D{d-f`|:9^HeLB5=0{WsDd-2b3ux');
define('LOGGED_IN_KEY',    '|%=:},4Q^1+x7/;Z0SfI>eE@w*y9n?cvxj6([12tF8Xr_)?(!Sg+@^-+9H^9,S7-');
define('NONCE_KEY',        '_gW-ITQ![+dN{N-NNpWq]wSz44G!Q0`r(FIQZr?/D5/[>+X4{-f~dT^`5W7d7ZcP');
define('AUTH_SALT',        'x_-yw`Wp%@:,l_memBwFnA*IRX,+kP#kl{}1qhTm/{]`QzDLY<1}L+QdQM5]kiDo');
define('SECURE_AUTH_SALT', 'UEkhiI%*bn.k2K>``W%2DPiRpghZ!b8%js9~#GA=XI@4--Q~^-3%e|p>3~Ew6sPl');
define('LOGGED_IN_SALT',   '`6lb`k/P+rMZQ^bIRI1.cL,-fIiB=a!,94JM++OwIN/+<F(G$o-g{;#-ai.Rj9s(');
define('NONCE_SALT',       ']eX8gv{60E)BUFWWe+Pd5fwXm@*?*W+~oa3;*Y}25:g)PN>@kX?x4bKf&6##=89g');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'sys_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
