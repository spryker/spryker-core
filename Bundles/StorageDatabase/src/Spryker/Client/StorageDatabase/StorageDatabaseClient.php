<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\StorageDatabase\Database\StorageDatabaseInterface;

/**
 * @method \Spryker\Client\StorageDatabase\StorageDatabaseFactory getFactory()
 */
class StorageDatabaseClient extends AbstractClient implements StorageDatabaseClientInterface
{
    /**
     * @var \Spryker\Client\StorageDatabase\Database\StorageDatabaseInterface
     */
    protected static $storageDatabaseService;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->getStorageDatabaseService()->get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array
    {
        return $this->getStorageDatabaseService()->getMulti($keys);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats(): void
    {
        $this->getStorageDatabaseService()->resetAccessStats();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getAccessStats(): array
    {
        return $this->getStorageDatabaseService()->getAccessStats();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        $this->getStorageDatabaseService()->setDebug($debug);
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Database\StorageDatabaseInterface
     */
    protected function getStorageDatabaseService(): StorageDatabaseInterface
    {
        if (static::$storageDatabaseService === null) {
            static::$storageDatabaseService = $this->getFactory()->createStorageDatabaseService();
        }

        return static::$storageDatabaseService;
    }
}
