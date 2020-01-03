<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Search;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Search\HealthCheck\HealthCheckInterface;
use Spryker\Yves\Search\HealthCheck\SearchHealthCheck;

/**
 * @method \Spryker\Yves\Search\SearchConfig getConfig()
 */
class SearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Search\HealthCheck\HealthCheckInterface
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
