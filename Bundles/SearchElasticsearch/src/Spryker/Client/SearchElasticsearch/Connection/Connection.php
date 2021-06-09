<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Connection;

use Elastica\Client;
use Generated\Shared\Transfer\SearchConnectionResponseTransfer;

class Connection implements ConnectionInterface
{
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
     * @return \Generated\Shared\Transfer\SearchConnectionResponseTransfer
     */
    public function checkConnection(): SearchConnectionResponseTransfer
    {
        $clientStatusData = $this->client->getStatus()->getData();

        $searchConnectionResponseTransfer = new SearchConnectionResponseTransfer();
        $searchConnectionResponseTransfer->setIsSuccessfull(true);
        $searchConnectionResponseTransfer->setRawResponse($clientStatusData);

        return $searchConnectionResponseTransfer;
    }
}
