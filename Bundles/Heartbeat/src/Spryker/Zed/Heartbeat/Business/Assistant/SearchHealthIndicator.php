<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Business\Assistant;

use Elastica\Client;
use Exception;
use Spryker\Shared\Heartbeat\Code\AbstractHealthIndicator;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;

class SearchHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{
    public const HEALTH_MESSAGE_UNABLE_TO_CONNECT_TO_SEARCH = 'Unable to connect to search';

    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @param \Elastica\Client $client
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
        $this->checkConnectToSearch();
    }

    /**
     * @return void
     */
    private function checkConnectToSearch()
    {
        try {
            $this->client->getStatus();
        } catch (Exception $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_CONNECT_TO_SEARCH);
            $this->addDysfunction($e->getMessage());
        }
    }
}
