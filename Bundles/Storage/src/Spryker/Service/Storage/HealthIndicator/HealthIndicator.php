<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Storage\HealthIndicator;

use Exception;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Service\Storage\Dependency\Client\StorageToStorageClientInterface;

class HealthIndicator implements HealthIndicatorInterface
{
    public const KEY_HEALTH_CHECK = 'healthCheck';

    /**
     * @var \Spryker\Service\Storage\Dependency\Client\StorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Service\Storage\Dependency\Client\StorageToStorageClientInterface $storageClient
     */
    public function __construct(StorageToStorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            $this->storageClient->set(static::KEY_HEALTH_CHECK, 'ok');
            $this->storageClient->get(static::KEY_HEALTH_CHECK);
        } catch (Exception $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }

        return (new HealthCheckServiceResponseTransfer())
            ->setStatus(true);
    }
}
