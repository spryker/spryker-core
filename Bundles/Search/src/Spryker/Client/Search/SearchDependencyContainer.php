<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Provider\IndexClientProvider;
use Spryker\Client\ZedRequest\Client\ZedClient;

class SearchDependencyContainer extends AbstractFactory
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
