<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service;

use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;

class SearchDependencyContainer extends AbstractDependencyContainer
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
