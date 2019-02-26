<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;

class StorageDatabaseConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Propel\PropelConfig::DB_ENGINE_PGSQL
     */
    protected const DB_ENGINE_PGSQL = 'pgsql';

    /**
     * @uses \Spryker\Zed\Propel\PropelConfig::DB_ENGINE_MYSQL
     */
    protected const DB_ENGINE_MYSQL = 'mysql';

    protected const RESOURCE_PREFIX_TO_STORAGE_TABLE_MAPPING = [
        'translation' => [
            self::KEY_STORAGE_TABLE_NAME => 'glossary',
        ],
        'product_search_config_extension' => [
            self::KEY_STORAGE_TABLE_NAME => 'product_search_config',
        ],
        'product_abstract_product_lists' => [
            self::KEY_STORAGE_TABLE_NAME => 'product_abstract_product_list',
        ],
        'product_concrete_product_lists' => [
            self::KEY_STORAGE_TABLE_NAME => 'product_concrete_product_list',
        ],
    ];

    protected const DEFAULT_STORAGE_TABLE_PREFIX = 'spy';
    protected const DEFAULT_STORAGE_TABLE_SUFFIX = 'storage';
    protected const STORAGE_TABLE_NAME_PART_SEPARATOR = '_';

    public const KEY_STORAGE_TABLE_PREFIX = 'KEY_STORAGE_TABLE_PREFIX';
    public const KEY_STORAGE_TABLE_SUFFIX = 'KEY_STORAGE_TABLE_SUFFIX';
    public const KEY_STORAGE_TABLE_NAME = 'KEY_STORAGE_TABLE_NAME';

    /**
     * @return array
     */
    public function getConnectionConfigForCurrentEngine(): array
    {
        $dbEngine = $this->getDatabaseEngine();
        $connectionData = $this->getConnectionConfigData();

        return $connectionData[$dbEngine] ?? [];
    }

    /**
     * @return string
     */
    public function getDatabaseEngine(): string
    {
        return $this->get(StorageDatabaseConstants::DB_ENGINE, '');
    }

    /**
     * @return bool
     */
    public function isConnectInDebugMode(): bool
    {
        return $this->get(StorageDatabaseConstants::DB_DEBUG, false);
    }

    /**
     * @return string[]
     */
    public function getResourcePrefixToStorageTableMapping(): array
    {
        return static::RESOURCE_PREFIX_TO_STORAGE_TABLE_MAPPING;
    }

    /**
     * @return string
     */
    public function getDefaultStorageTablePrefix(): string
    {
        return static::DEFAULT_STORAGE_TABLE_PREFIX;
    }

    /**
     * @return string
     */
    public function getDefaultStorageTableSuffix(): string
    {
        return static::DEFAULT_STORAGE_TABLE_SUFFIX;
    }

    /**
     * @return string
     */
    public function getStorageTableNamePartSeparator(): string
    {
        return static::STORAGE_TABLE_NAME_PART_SEPARATOR;
    }

    /**
     * @return array
     */
    protected function getConnectionConfigData(): array
    {
        return [
            static::DB_ENGINE_PGSQL => [
                'adapter' => static::DB_ENGINE_PGSQL,
                'dsn' => $this->getDsn(),
                'user' => $this->get(StorageDatabaseConstants::DB_USERNAME),
                'password' => $this->get(StorageDatabaseConstants::DB_PASSWORD),
                'settings' => [],
            ],
            static::DB_ENGINE_MYSQL => [
                'adapter' => static::DB_ENGINE_MYSQL,
                'dsn' => $this->getDsn(),
                'user' => $this->get(StorageDatabaseConstants::DB_USERNAME),
                'password' => $this->get(StorageDatabaseConstants::DB_PASSWORD),
                'settings' => [
                    'charset' => 'utf8',
                    'queries' => [
                        'utf8' => 'SET NAMES utf8 COLLATE utf8_unicode_ci, COLLATION_CONNECTION = utf8_unicode_ci, COLLATION_DATABASE = utf8_unicode_ci, COLLATION_SERVER = utf8_unicode_ci',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    protected function getDsn(): string
    {
        return sprintf(
            '%s:host=%s;port=%d;dbname=%s',
            $this->get(StorageDatabaseConstants::DB_ENGINE),
            $this->get(StorageDatabaseConstants::DB_HOST),
            $this->get(StorageDatabaseConstants::DB_PORT),
            $this->get(StorageDatabaseConstants::DB_DATABASE)
        );
    }
}
