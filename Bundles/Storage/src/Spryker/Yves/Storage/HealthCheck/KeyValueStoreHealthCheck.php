<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Storage\HealthCheck;

use Exception;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Client\Storage\StorageClientInterface;

class KeyValueStoreHealthCheck implements HealthCheckInterface
{
    public const KEY_STORAGE_HEALTH_CHECK = 'STORAGE_YVES_HEALTH_CHECK';

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    public function __construct(StorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        $healthCheckServiceResponseTransfer = (new HealthCheckServiceResponseTransfer())
            ->setStatus(true);

        try {
            $this->storageClient->set(static::KEY_STORAGE_HEALTH_CHECK, 'ok');
            $this->storageClient->get(static::KEY_STORAGE_HEALTH_CHECK);
        } catch (Exception $e) {
            return $healthCheckServiceResponseTransfer
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }

        return $healthCheckServiceResponseTransfer
            ->setStatus(true);
    }
}
