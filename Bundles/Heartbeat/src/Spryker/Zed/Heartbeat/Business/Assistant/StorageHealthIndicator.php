<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Business\Assistant;

use Exception;
use Predis\Client;
use Spryker\Shared\Heartbeat\Code\AbstractHealthIndicator;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;

class StorageHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{
    public const HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_STORAGE = 'Unable to write to storage';
    public const HEALTH_MESSAGE_UNABLE_TO_READ_FROM_STORAGE = 'Unable to read from storage';
    public const KEY_HEARTBEAT = 'heartbeat';

    /**
     * @var \Predis\Client
     */
    protected $client;

    /**
     * @param \Predis\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
            $this->client->set(self::KEY_HEARTBEAT, 'ok');
        } catch (Exception $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_STORAGE);
            $this->addDysfunction($e->getMessage());
        }
    }

    /**
     * @return void
     */
    private function checkReadFromStorage()
    {
        try {
            $this->client->get(self::KEY_HEARTBEAT);
        } catch (Exception $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_READ_FROM_STORAGE);
            $this->addDysfunction($e->getMessage());
        }
    }
}
