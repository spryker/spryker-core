<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToSearchClientInterface;

class SearchRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCustomerClientInterface
     */
    protected function getSearchClient(): SearchRestApiToSearchClientInterface
    {
        return $this->getProvidedDependency(SearchRestApiDependencyProvider::CLIENT_SEARCH_CLIENT);
    }
}
