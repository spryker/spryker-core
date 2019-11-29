<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\HealthCheckIndicator;

use Elastica\Client;
use Exception;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

class SearchHealthIndicator implements HealthIndicatorInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $searchClient;

    /**
     * @param \Elastica\Client $searchClient
     */
    public function __construct(Client $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            $this->searchClient->getStatus()->getData();
        } catch (Exception $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }

        return (new HealthCheckServiceResponseTransfer())
            ->setStatus(true);
    }
}
