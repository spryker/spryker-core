<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\ResourceToTableMapper;

use Spryker\Client\StorageDatabase\StorageDatabaseConfig;

class ResourceToTableMapper implements ResourceToTableMapperInterface
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
    public function map(string $resourceKey): string
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
     * @return string
     */
    protected function getStorageTableNameByResourceName(string $resourceName): string
    {
        return $this->config->getStorageTableNameByResourceName($resourceName);
    }
}
