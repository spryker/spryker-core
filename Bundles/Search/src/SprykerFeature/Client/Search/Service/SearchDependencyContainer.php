<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service;

use Generated\Client\Ide\FactoryAutoCompletion\ZedRequest;
use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Shared\Yves\YvesConfig;

/**
 * @method ZedRequest getFactory()
 */
class SearchDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ZedClient
     */
    public function createIndexClient()
    {
//        $httpClient = $this->getFactory()->createClientHttpClient(
//            $this->getFactory(),
//            $this->getLocator(),
//            'http://' . Config::get(SystemConfig::HOST_ZED_API)
//        );
//
//
//        return $this->getFactory()->createClientZedClient(
//            $httpClient
//        );
        return $this->createProviderIndexClientProvider()->getClient();
    }

    protected function createProviderIndexClientProvider()
    {
        return $this->getFactory()->createProviderIndexClientProvider($this->getFactory(), $this->getLocator());
    }
}
