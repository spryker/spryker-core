<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service;

use Generated\Client\Ide\FactoryAutoCompletion\ZedRequestService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;
use SprykerFeature\Client\ZedRequest\ZedRequestDependencyProvider;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

/**
 * @method ZedRequestService getFactory()
 */
class ZedRequestDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return ZedClient
     */
    public function createClient()
    {
        $httpClient = $this->getFactory()->createClientHttpClient(
            $this->getFactory(),
            $this->getProvidedDependency(ZedRequestDependencyProvider::CLIENT_AUTH),
            'http://' . Config::get(SystemConfig::HOST_ZED_API)
        );

        return $this->getFactory()->createClientZedClient(
            $httpClient
        );
    }

}
