<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
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

    protected function createProviderIndexClientProvider()
    {
        return $this->getFactory()->createProviderIndexClientProvider($this->getFactory(), $this->getLocator());
    }

}
