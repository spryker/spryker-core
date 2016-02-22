<?php

use Spryker\Shared\Propel\PropelConstants;

$currentStore = \Spryker\Shared\Kernel\Store::getInstance()->getStoreName();

$config[PropelConstants::PROPEL_DEBUG] = true;
$config[PropelConstants::PROPEL_SHOW_EXTENDED_EXCEPTION] = true;

$config[PropelConstants::PROPEL] = [
    'database' => [
        'connections' => [
            'default' => [
                'adapter' => 'mysql',
                'dsn' => 'mysql:host=127.0.0.1;dbname=DE_development_zed',
                'user' => 'development',
                'password' => '',
                'settings' => [
                    'charset' => 'utf8',
                    'queries' => [
                        'utf8' => 'SET NAMES utf8 COLLATE utf8_unicode_ci, COLLATION_CONNECTION = utf8_unicode_ci, COLLATION_DATABASE = utf8_unicode_ci, COLLATION_SERVER = utf8_unicode_ci',
                    ],
                ],
            ],
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
                'object' => '\Spryker\Zed\Propel\Business\Builder\ObjectBuilder',
            ],
        ],
    ],
    'paths' => [
        'phpDir' => APPLICATION_ROOT_DIR,
        'sqlDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/' . $currentStore . '/Sql',
        'migrationDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/' . $currentStore . '/Migration',
        'schemaDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/' . $currentStore . '/Schema',
        'phpConfDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/' . $currentStore . '/Config',
    ],
];

$config[PropelConstants::ZED_DB_ENGINE] = 'mysql';
$config[PropelConstants::ZED_DB_USERNAME] = 'development';
$config[PropelConstants::ZED_DB_PASSWORD] = 'mate20mg';
$config[PropelConstants::ZED_DB_DATABASE] = 'DE_development_zed';
$config[PropelConstants::ZED_DB_HOST] = '127.0.0.1';
$config[PropelConstants::ZED_DB_PORT] = 3306;
