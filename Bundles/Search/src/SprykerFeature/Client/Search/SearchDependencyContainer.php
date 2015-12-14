<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search;

use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Search\Provider\IndexClientProvider;
use SprykerFeature\Client\ZedRequest\Client\ZedClient;

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
        return new IndexClientProvider($this->getFactory(), $this->getLocator());
    }

}
