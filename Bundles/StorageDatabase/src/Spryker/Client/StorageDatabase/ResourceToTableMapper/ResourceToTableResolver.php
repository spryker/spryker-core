<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\ResourceToTableMapper;

use Spryker\Client\StorageDatabase\StorageDatabaseConfig;

class ResourceToTableResolver implements ResourceToTableResolverInterface
{
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
    public function resolve(string $resourceKey): string
    {
        $resourcePrefix = $this->getResourcePrefixFromKey($resourceKey);

        return $this->getStorageTableNameByResourcePrefix($resourcePrefix);
    }

    /**
     * @param string $resourceKey
     *
     * @return string
     */
    protected function getResourcePrefixFromKey(string $resourceKey): string
    {
        [$resourceName] = explode(':', $resourceKey);

        return $resourceName;
    }

    /**
     * @param string $resourcePrefix
     *
     * @return string
     */
    protected function getStorageTableNameByResourcePrefix(string $resourcePrefix): string
    {
        $resourcePrefixToTableMapping = $this->config->getResourcePrefixToStorageTableMapping();
        $tableName = $resourcePrefixToTableMapping[$resourcePrefix] ?? $resourcePrefix;

        return implode('_', [
            $this->config->getStorageTablePrefix(),
            $tableName,
            $this->config->getStorageTableSuffix(),
        ]);
    }
}
