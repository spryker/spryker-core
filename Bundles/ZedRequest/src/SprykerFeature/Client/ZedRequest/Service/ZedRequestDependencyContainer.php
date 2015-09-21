<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service;

use Generated\Client\Ide\FactoryAutoCompletion\ZedRequestService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerEngine\Shared\Config;
use SprykerFeature\Client\ZedRequest\Service\Client\HttpClient;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;
use SprykerFeature\Client\ZedRequest\ZedRequestDependencyProvider;

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
        return $this->getFactory()->createClientZedClient(
            $this->createHttpClient()
        );
    }

    /**
     * @return HttpClient
     *
     * @todo remove Factory usage: https://spryker.atlassian.net/browse/CD-439
     */
    protected function createHttpClient()
    {
        $httpClient = $this->getFactory()->createClientHttpClient(
            $this->getFactory(),
            $this->getProvidedDependency(ZedRequestDependencyProvider::CLIENT_AUTH),
            $this->getConfig()->getZedRequestBaseUrl(),
            $this->getConfig()->getRawToken()
        );

        return $httpClient;
    }

    /**
     * @return ZedRequestConfig
     */
    protected function getConfig()
    {
        return $this->getFactory()->createZedRequestConfig(Config::getInstance());
    }

}
