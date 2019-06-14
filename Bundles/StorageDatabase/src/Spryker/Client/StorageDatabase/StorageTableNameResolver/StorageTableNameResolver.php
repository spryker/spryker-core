<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\StorageTableNameResolver;

use Spryker\Client\StorageDatabase\Exception\InvalidRecourseToTableMappingConfigurationException;
use Spryker\Client\StorageDatabase\StorageDatabaseConfig;

class StorageTableNameResolver implements StorageTableNameResolverInterface
{
    protected const MESSAGE_INVALID_RESOURCE_TO_TABLE_CONFIGURATION_MAPPING_EXCEPTION = 'Invalid resource to table mapping configuration.';

    /**
     * @var \Spryker\Client\StorageDatabase\StorageDatabaseConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\StorageDatabase\StorageDatabaseConfig $config
     */
    public function __construct(StorageDatabaseConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $resourceKey
     *
     * @return string
     */
    public function resolveByResourceKey(string $resourceKey): string
    {
        $resourceName = $this->getResourceNameFromKey($resourceKey);

        return $this->getStorageTableNameByResourceName($resourceName);
    }

    /**
     * @param string $resourceKey
     *
     * @return string
     */
    protected function getResourceNameFromKey(string $resourceKey): string
    {
        [$resourceName] = explode(':', $resourceKey);

        return $resourceName;
    }

    /**
     * @param string $resourceName
     *
     * @throws \Spryker\Client\StorageDatabase\Exception\InvalidRecourseToTableMappingConfigurationException
     *
     * @return string
     */
    protected function getStorageTableNameByResourceName(string $resourceName): string
    {
        $resourcePrefixToTableMapping = $this->config->getResourceNameToStorageTableMap();

        if (!array_key_exists($resourceName, $resourcePrefixToTableMapping)) {
            return $this->buildStorageTableName($resourceName);
        }

        $storageTableNameParts = $resourcePrefixToTableMapping[$resourceName];
        $storageTablePrefix = $storageTableNameParts[StorageDatabaseConfig::KEY_STORAGE_TABLE_PREFIX] ?? '';
        $storageTableSuffix = $storageTableNameParts[StorageDatabaseConfig::KEY_STORAGE_TABLE_SUFFIX] ?? '';
        $storageTableName = $storageTableNameParts[StorageDatabaseConfig::KEY_STORAGE_TABLE_NAME] ?? '';

        if (!$storageTableName) {
            throw new InvalidRecourseToTableMappingConfigurationException(static::MESSAGE_INVALID_RESOURCE_TO_TABLE_CONFIGURATION_MAPPING_EXCEPTION);
        }

        return $this->buildStorageTableName($storageTableName, $storageTablePrefix, $storageTableSuffix);
    }

    /**
     * @param string $resourcePrefix
     * @param string $storageTablePrefix
     * @param string $storageTableSuffix
     *
     * @return string
     */
    protected function buildStorageTableName(string $resourcePrefix, string $storageTablePrefix = '', string $storageTableSuffix = ''): string
    {
        $storageTablePrefix = $storageTablePrefix ?: $this->config->getDefaultStorageTablePrefix();
        $storageTableSuffix = $storageTableSuffix ?: $this->config->getDefaultStorageTableSuffix();
        $storageTableNamePartSeparator = $this->config->getStorageTableNamePartSeparator();

        return implode($storageTableNamePartSeparator, array_filter([
            $storageTablePrefix,
            $resourcePrefix,
            $storageTableSuffix,
        ]));
    }
}
