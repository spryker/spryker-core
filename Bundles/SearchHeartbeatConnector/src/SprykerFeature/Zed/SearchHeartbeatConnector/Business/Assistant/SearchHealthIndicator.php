<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchHeartbeatConnector\Business\Assistant;

use Elastica\Client;
use SprykerFeature\Shared\Heartbeat\Code\AbstractHealthIndicator;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;

class SearchHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{

    const HEALTH_MESSAGE_UNABLE_TO_CONNECT_TO_SEARCH = 'Unable to connect to search';

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
        $this->checkConnectToSearch();
    }

    private function checkConnectToSearch()
    {
        try {
            $this->client->getStatus();
        } catch (\Exception $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_CONNECT_TO_SEARCH);
            $this->addDysfunction($e->getMessage());
        }
    }

}
