<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\Client\HttpClient;
use Spryker\Client\ZedRequest\Client\ZedClient;
use Spryker\Client\ZedRequest\Header\AuthToken\AuthToken;
use Spryker\Client\ZedRequest\Header\AuthToken\AuthTokenInterface;
use Spryker\Client\ZedRequest\Header\RequestId\RequestId;
use Spryker\Client\ZedRequest\Header\RequestId\RequestIdInterface;
use Spryker\Client\ZedRequest\Messenger\Messenger;
use Spryker\Client\ZedRequest\Messenger\MessengerInterface;
use Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface;
use Spryker\Shared\ZedRequest\Client\LoggableZedClient;
use Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface;
use Spryker\Shared\ZedRequest\Logger\ZedRequestInMemoryLogger;
use Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface;

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
        if (!$this->getConfig()->isDevelopmentMode()) {
            return $this->createZedClient();
        }

        return $this->createLoggableZedClient();
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface
     */
    public function createZedClient(): AbstractZedClientInterface
    {
        return new ZedClient(
            $this->createHttpClient()
        );
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface
     */
    public function createLoggableZedClient(): AbstractZedClientInterface
    {
        return new LoggableZedClient(
            $this->createZedClient(),
            $this->createZedRequestLogger()
        );
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface
     */
    public function createZedRequestLogger(): ZedRequestLoggerInterface
    {
        return new ZedRequestInMemoryLogger(
            $this->getUtilEncodingService(),
            $this->getConfig()->getZedRequestBaseUrl()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\Client\HttpClientInterface|\Spryker\Shared\ZedRequest\Client\HttpClientInterface
     */
    public function createHttpClient()
    {
        return new HttpClient(
            $this->getConfig(),
            $this->getHeaderExpanderPlugins(),
            $this->getUtilNetworkService(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequestExtension\Dependency\Plugin\HeaderExpanderPluginInterface[]
     */
    public function getHeaderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::PLUGINS_HEADER_EXPANDER);
    }

    /**
     * @return \Spryker\Client\ZedRequest\Header\AuthToken\AuthTokenInterface
     */
    public function createAuthToken(): AuthTokenInterface
    {
        return new AuthToken($this->getConfig(), $this->getUtilTextService());
    }

    /**
     * @return \Spryker\Client\ZedRequest\Header\RequestId\RequestIdInterface
     */
    public function createRequestId(): RequestIdInterface
    {
        return new RequestId($this->getUtilNetworkService());
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
    public function getUtilNetworkService()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_NETWORK);
    }

    /**
     * @return \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    public function getUtilTextService()
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
     * @return \Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ZedRequestToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
