<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;
use Spryker\Zed\Propel\PropelConfig;

class StorageDatabaseConfig extends AbstractBundleConfig
{
    protected const RESOURCE_PREFIX_TO_STORAGE_TABLE_MAPPING = [
        'translation' => 'glossary',
        'product_search_config_extension' => 'product_search_config',
        'product_abstract_product_lists' => 'product_abstract_product_list',
        'product_concrete_product_lists' => 'product_concrete_product_list',
    ];

    protected const STORAGE_TABLE_PREFIX = 'spy';

    protected const STORAGE_TABLE_SUFFIX = 'storage';

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
     * @param string $resourceName
     *
     * @return string[]
     */
    public function getResourcePrefixToStorageTableMapping(): array
    {
        return static::RESOURCE_PREFIX_TO_STORAGE_TABLE_MAPPING;
    }

    /**
     * @return string
     */
    public function getStorageTablePrefix(): string
    {
        return static::STORAGE_TABLE_PREFIX;
    }

    /**
     * @return string
     */
    public function getStorageTableSuffix(): string
    {
        return static::STORAGE_TABLE_SUFFIX;
    }

    /**
     * @return array
     */
    protected function getConnectionConfigData(): array
    {
        return [
            PropelConfig::DB_ENGINE_PGSQL => [
                'adapter' => PropelConfig::DB_ENGINE_PGSQL,
                'dsn' => $this->getDsn(),
                'user' => $this->get(StorageDatabaseConstants::DB_USERNAME),
                'password' => $this->get(StorageDatabaseConstants::DB_PASSWORD),
                'settings' => [],
            ],
            PropelConfig::DB_ENGINE_MYSQL => [
                'adapter' => PropelConfig::DB_ENGINE_MYSQL,
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
