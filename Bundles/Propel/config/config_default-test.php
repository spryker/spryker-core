<?php

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\PropelConfig;
use Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\QueryBuilder;

$config[PropelConstants::ZED_DB_ENGINE_MYSQL] = PropelConfig::DB_ENGINE_MYSQL;
$config[PropelConstants::ZED_DB_ENGINE_PGSQL] = PropelConfig::DB_ENGINE_PGSQL;
$config[PropelConstants::ZED_DB_SUPPORTED_ENGINES] = [
    PropelConfig::DB_ENGINE_MYSQL => 'MySql',
    PropelConfig::DB_ENGINE_PGSQL => 'PostgreSql',
];

$config[PropelConstants::ZED_DB_USERNAME] = (getenv(PropelConstants::ZED_DB_USERNAME)) ?: 'development';
$config[PropelConstants::ZED_DB_PASSWORD] = (getenv(PropelConstants::ZED_DB_PASSWORD)) ?: 'mate20mg';
$config[PropelConstants::ZED_DB_DATABASE] = (getenv(PropelConstants::ZED_DB_DATABASE)) ?: 'DE_test_zed';
$config[PropelConstants::ZED_DB_HOST] = (getenv(PropelConstants::ZED_DB_HOST)) ?: '127.0.0.1';
$config[PropelConstants::USE_SUDO_TO_MANAGE_DATABASE] = false;

$config[PropelConstants::ZED_DB_PORT] = (getenv(PropelConstants::ZED_DB_PORT)) ?: 5432;
$config[PropelConstants::ZED_DB_ENGINE] = (getenv(PropelConstants::ZED_DB_ENGINE)) ?: $config[PropelConstants::ZED_DB_ENGINE_PGSQL];

$currentStore = Store::getInstance()->getStoreName();

$dsn = sprintf(
    '%s:host=%s;port=%d;dbname=%s',
    $config[PropelConstants::ZED_DB_ENGINE],
    $config[PropelConstants::ZED_DB_HOST],
    $config[PropelConstants::ZED_DB_PORT],
    $config[PropelConstants::ZED_DB_DATABASE]
);

$connections = [
    'pgsql' => [
        'adapter' => PropelConfig::DB_ENGINE_PGSQL,
        'dsn' => $dsn,
        'user' => $config[PropelConstants::ZED_DB_USERNAME],
        'password' => $config[PropelConstants::ZED_DB_PASSWORD],
        'settings' => [],
    ],
    'mysql' => [
        'adapter' => PropelConfig::DB_ENGINE_MYSQL,
        'dsn' => $dsn,
        'user' => $config[PropelConstants::ZED_DB_USERNAME],
        'password' => $config[PropelConstants::ZED_DB_PASSWORD],
        'settings' => [
            'charset' => 'utf8',
            'queries' => [
                'utf8' => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, COLLATION_CONNECTION = utf8mb4_unicode_ci, COLLATION_DATABASE = utf8mb4_unicode_ci, COLLATION_SERVER = utf8mb4_unicode_ci',
                'mode' => "set sql_mode = ''",
            ],
        ],
    ],
];

$engine = $config[PropelConstants::ZED_DB_ENGINE];
$config[PropelConstants::PROPEL] = [
    'database' => [
        'connections' => [
            'default' => $connections[$engine],
            'zed' => $connections[$engine],
        ],
    ],
    'runtime' => [
        'defaultConnection' => 'default',
        'connections' => ['default', 'zed'],
    ],
    'generator' => [
        'defaultConnection' => 'default',
        'connections' => ['default', 'zed'],
        'objectModel' => [
            'defaultKeyType' => 'fieldName',
            'builders' => [
                'object' => ObjectBuilder::class,
                'query' => QueryBuilder::class,
            ],
        ],
    ],
    'paths' => [
        'phpDir' => APPLICATION_ROOT_DIR,
        'sqlDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/' . $currentStore . '/Sql',
        'migrationDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/' . $currentStore . '/Migration_' . $config[PropelConstants::ZED_DB_ENGINE],
        'schemaDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/' . $currentStore . '/Schema',
        'phpConfDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/' . $currentStore . '/Config',
    ],
];
