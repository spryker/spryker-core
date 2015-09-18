<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service;

use Generated\Client\Ide\FactoryAutoCompletion\SearchService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Search\Service\Provider\IndexClientProvider;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;

/**
 * @method SearchService getFactory()
 */
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
        return $this->getFactory()->createProviderIndexClientProvider($this->getFactory(), $this->getLocator());
    }

}
