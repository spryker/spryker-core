<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Search\HealthIndicator;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Service\Search\Dependency\Client\SearchToSearchClientInterface;

class HealthIndicator implements HealthIndicatorInterface
{
    /**
     * @var \Spryker\Service\Search\Dependency\Client\SearchToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @param \Spryker\Service\Search\Dependency\Client\SearchToSearchClientInterface $searchClient
     */
    public function __construct(SearchToSearchClientInterface $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            $this->searchClient->checkConnection();
        } catch (\Exception $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }

        return (new HealthCheckServiceResponseTransfer())
            ->setStatus(true);
    }
}
