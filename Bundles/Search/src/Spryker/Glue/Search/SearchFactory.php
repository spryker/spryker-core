<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Search;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\Search\HealthCheck\HealthCheckInterface;
use Spryker\Glue\Search\HealthCheck\SearchHealthCheck;

/**
 * @method \Spryker\Glue\Search\SearchConfig getConfig()
 */
class SearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\Search\HealthCheck\HealthCheckInterface
     */
    public function createSearchHealthChecker(): HealthCheckInterface
    {
        return new SearchHealthCheck(
            $this->getSearchClient()
        );
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH);
    }
}
