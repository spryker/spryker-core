<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\QueryDataResolver;

use Spryker\Client\StorageDatabase\StorageDatabaseConfig;

class QueryDataMapper implements QueryDataMapperInterface
{
    /**
     * @var \Spryker\Client\StorageDatabase\StorageDatabaseConfig
     */
    private $config;

    public function __construct(StorageDatabaseConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function map(string $key): string
    {
        $resourceName = $this->getResourceNameFromKey($key);

        return $this->getStorageTableNameByResourceName($resourceName);
    }

    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function mapMany(array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            $tableName = $this->map($key);
            $result[$tableName][] = $key;
        }

        return array_merge_recursive(...$result);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getResourceNameFromKey(string $key): string
    {
        [$resourceName] = explode(':', $key);

        return (string)$resourceName;
    }

    /**
     * @param string $resourceName
     *
     * @return string
     */
    protected function getStorageTableNameByResourceName(string $resourceName): string
    {
        return $this->config->getStorageTableNameByResourceName($resourceName);
    }
}
