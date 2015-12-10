<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Search\Service\Provider\IndexClientProvider;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;

class SearchDependencyContainer extends AbstractServiceDependencyContainer
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
