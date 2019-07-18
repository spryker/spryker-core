<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Business\Assistant;

use Exception;
use Spryker\Shared\Heartbeat\Code\AbstractHealthIndicator;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;
use Spryker\Zed\Heartbeat\Dependency\Client\HeartbeatToStorageClientInterface;

class StorageHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{
    public const HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_STORAGE = 'Unable to write to storage';
    public const HEALTH_MESSAGE_UNABLE_TO_READ_FROM_STORAGE = 'Unable to read from storage';
    public const KEY_HEARTBEAT = 'heartbeat';

    /**
     * @var \Spryker\Zed\Heartbeat\Dependency\Client\HeartbeatToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Zed\Heartbeat\Dependency\Client\HeartbeatToStorageClientInterface $storageClient
     */
    public function __construct(HeartbeatToStorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @return void
     */
    public function healthCheck()
    {
        $this->checkWriteToStorage();
        $this->checkReadFromStorage();
    }

    /**
     * @return void
     */
    private function checkWriteToStorage()
    {
        try {
            $this->storageClient->set(static::KEY_HEARTBEAT, 'ok');
        } catch (Exception $e) {
            $this->addDysfunction(static::HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_STORAGE);
            $this->addDysfunction($e->getMessage());
        }
    }

    /**
     * @return void
     */
    private function checkReadFromStorage()
    {
        try {
            $this->storageClient->get(static::KEY_HEARTBEAT);
        } catch (Exception $e) {
            $this->addDysfunction(static::HEALTH_MESSAGE_UNABLE_TO_READ_FROM_STORAGE);
            $this->addDysfunction($e->getMessage());
        }
    }
}
