<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\Client\HttpClient;
use Spryker\Client\ZedRequest\Client\ZedClient;
use Spryker\Client\ZedRequest\HealthIndicator\HealthIndicatorInterface;
use Spryker\Client\ZedRequest\HealthIndicator\ZedRequestHealthIndicator;
use Spryker\Client\ZedRequest\Messenger\Messenger;
use Spryker\Client\ZedRequest\Messenger\MessengerInterface;

/**
 * @method \Spryker\Client\ZedRequest\ZedRequestConfig getConfig()
 */
class ZedRequestFactory extends AbstractFactory
{
    /**
     * @var \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface|null
     */
    protected static $zedClient;

    /**
     * @return \Spryker\Client\ZedRequest\Messenger\MessengerInterface
     */
    public function createMessenger(): MessengerInterface
    {
        return new Messenger(
            $this->getCashedClient(),
            $this->getMessengerClient()
        );
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface
     */
    public function getCashedClient()
    {
        if (!static::$zedClient) {
            static::$zedClient = $this->createClient();
        }

        return static::$zedClient;
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface
     */
    public function createClient()
    {
        return new ZedClient(
            $this->createHttpClient()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\Client\HttpClientInterface|\Spryker\Shared\ZedRequest\Client\HttpClientInterface
     */
    protected function createHttpClient()
    {
        $httpClient = new HttpClient(
            $this->getConfig()->getZedRequestBaseUrl(),
            $this->getConfig()->getRawToken(),
            true,
            $this->getUtilTextService(),
            $this->getUtilNetworkService(),
            $this->getConfig()->getTokenOptions(),
            $this->getConfig()->getClientConfiguration()
        );

        return $httpClient;
    }

    /**
     * @deprecated Use `$this->getConfig()->getTokenOptions()` instead.
     *
     * @return array
     */
    protected function createTokenOptions()
    {
        return $this->getConfig()->getTokenOptions();
    }

    /**
     * @return \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface
     */
    protected function getUtilNetworkService()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_NETWORK);
    }

    /**
     * @return \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_TEXT);
    }

    /**
     * @return \Spryker\Client\ZedRequest\Dependency\Plugin\MetaDataProviderPluginInterface[]
     */
    public function getMetaDataProviderPlugins()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::META_DATA_PROVIDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\ZedRequest\Dependency\Client\ZedRequestToMessengerClientInterface
     */
    public function getMessengerClient()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \Spryker\Client\ZedRequest\HealthIndicator\HealthIndicatorInterface
     */
    public function createZedRequestHealthCheckIndicator(): HealthIndicatorInterface
    {
        return new ZedRequestHealthIndicator(
            $this->createClient()
        );
    }
}
