<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Search;

use Spryker\Client\Search\SearchClientInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Search\HealthIndicator\HealthIndicator;
use Spryker\Service\Search\HealthIndicator\HealthIndicatorInterface;

class SearchServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Search\HealthIndicator\HealthIndicatorInterface
     */
    public function createStorageHealthIndicator(): HealthIndicatorInterface
    {
        return new HealthIndicator(
            $this->getSearchClient()
        );
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient(): SearchClientInterface
    {
        return $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH);
    }
}
