<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;

/**
 * @method \Spryker\Shared\StorageDatabase\StorageDatabaseConfig getSharedConfig()
 */
class StorageDatabaseConfig extends AbstractBundleConfig
{
    protected const DEFAULT_STORAGE_TABLE_PREFIX = 'spy';
    protected const DEFAULT_STORAGE_TABLE_SUFFIX = 'storage';
    protected const STORAGE_TABLE_NAME_PART_SEPARATOR = '_';

    /**
     * Example:
     *
     * [
     *     'translation' => [
     *         StorageDatabaseConfig::KEY_STORAGE_TABLE_NAME => 'glossary',
     *     ],
     *     'product_search_config_extension' => [
     *         StorageDatabaseConfig::KEY_STORAGE_TABLE_PREFIX => 'pyz',
     *         StorageDatabaseConfig::KEY_STORAGE_TABLE_NAME => 'product_search_config',
     *         StorageDatabaseConfig::KEY_STORAGE_TABLE_SUFFIX => 'storage',
     *     ],
     * ];
     */
    protected const RESOURCE_PREFIX_TO_STORAGE_TABLE_MAP = [];

    /**
     * @return array
     */
    public function getConnectionConfigForCurrentEngine(): array
    {
        $dbEngine = $this->getDbEngineName();
        $connectionData = $this->getConnectionConfigData();

        return $connectionData[$dbEngine] ?? [];
    }

    /**
     * @return bool
     */
    public function isDbDebug(): bool
    {
        return $this->get(StorageDatabaseConstants::DB_DEBUG, false);
    }

    /**
     * Specification:
     * - Returns a map of resources prefixes to storage/search table names.
     *
     * @api
     *
     * @return string[][]
     */
    public function getResourceNameToStorageTableMap(): array
    {
        return static::RESOURCE_PREFIX_TO_STORAGE_TABLE_MAP;
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
     * @return string
     */
    public function getDbEngineName(): string
    {
        return $this->get(StorageDatabaseConstants::DB_ENGINE, '');
    }

    /**
     * @return string
     */
    public function getStorageTablePrefixConfigKey(): string
    {
        return $this->getSharedConfig()->getStorageTablePrefixConfigKey();
    }

    /**
     * @return string
     */
    public function getStorageTableSuffixConfigKey(): string
    {
        return $this->getSharedConfig()->getStorageTableSuffixConfigKey();
    }

    /**
     * @return string
     */
    public function getStorageTableNameConfigKey(): string
    {
        return $this->getSharedConfig()->getStorageTableNameConfigKey();
    }

    /**
     * @return array
     */
    protected function getConnectionConfigData(): array
    {
        $postgreSqlDbEngineName = $this->getSharedConfig()->getPostgreSqlDbEngineName();
        $mySqlDbEngineName = $this->getSharedConfig()->getMySqlDbEngineName();

        return [
            $postgreSqlDbEngineName => [
                'adapter' => $postgreSqlDbEngineName,
                'dsn' => $this->getDsn(),
                'user' => $this->get(StorageDatabaseConstants::DB_USERNAME),
                'password' => $this->get(StorageDatabaseConstants::DB_PASSWORD),
                'settings' => [],
            ],
            $mySqlDbEngineName => [
                'adapter' => $mySqlDbEngineName,
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
            $this->getDbEngineName(),
            $this->get(StorageDatabaseConstants::DB_HOST),
            $this->get(StorageDatabaseConstants::DB_PORT),
            $this->get(StorageDatabaseConstants::DB_DATABASE)
        );
    }
}
