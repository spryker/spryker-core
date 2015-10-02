<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\StorageHeartbeatConnector\Business\Assistant;

use Predis\Client;
use SprykerFeature\Shared\Heartbeat\Code\AbstractHealthIndicator;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;

class StorageHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{

    const HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_STORAGE = 'Unable to write to storage';
    const HEALTH_MESSAGE_UNABLE_TO_READ_FROM_STORAGE = 'Unable to read from storage';
    const KEY_HEARTBEAT = 'heartbeat';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function healthCheck()
    {
        $this->checkWriteToStorage();
        $this->checkReadFromStorage();
    }

    private function checkWriteToStorage()
    {
        try {
            $this->client->set(self::KEY_HEARTBEAT, 'ok');
        } catch (\Exception $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_STORAGE);
            $this->addDysfunction($e->getMessage());
        }
    }

    private function checkReadFromStorage()
    {
        try {
            $this->client->get(self::KEY_HEARTBEAT);
        } catch (\Exception $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_READ_FROM_STORAGE);
            $this->addDysfunction($e->getMessage());
        }
    }

}
