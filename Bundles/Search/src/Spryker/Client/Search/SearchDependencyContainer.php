<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractDependencyContainer;
use Spryker\Client\Search\Provider\IndexClientProvider;
use Spryker\Client\ZedRequest\Client\ZedClient;

class SearchDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ZedClient
     */
    public function createIndexClient()
    {
        return $this->createProviderIndexClientProvider()->getClient();
    }

    /**
     * @return IndexClientProvider
     */
    protected function createProviderIndexClientProvider()
    {
        return new IndexClientProvider($this->getLocator());
    }

}
