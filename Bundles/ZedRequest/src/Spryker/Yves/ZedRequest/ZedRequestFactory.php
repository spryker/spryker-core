<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ZedRequest;

use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer;
use Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface;
use Spryker\Shared\ZedRequest\Logger\ZedRequestInMemoryLogger;
use Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ZedRequest\HealthCheck\HealthCheckInterface;
use Spryker\Yves\ZedRequest\HealthCheck\ZedRequestHealthCheck;
use Spryker\Yves\ZedRequest\Plugin\ZedRequestHeaderMiddleware;
use Spryker\Yves\ZedRequest\Plugin\ZedRequestLogPlugin;
use Spryker\Yves\ZedRequest\Plugin\ZedResponseLogPlugin;
use Spryker\Yves\ZedRequest\WebProfiler\ZedRequestDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

class ZedRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer
     */
    public function createHandlerStackContainer()
    {
        return new HandlerStackContainer();
    }

    /**
     * @return \Spryker\Yves\ZedRequest\Plugin\ZedRequestLogPlugin|\Spryker\Shared\ZedRequest\Client\Middleware\MiddlewareInterface
     */
    public function createRequestLogPlugin()
    {
        return new ZedRequestLogPlugin();
    }

    /**
     * @return \Spryker\Yves\ZedRequest\Plugin\ZedResponseLogPlugin|\Spryker\Shared\ZedRequest\Client\Middleware\MiddlewareInterface
     */
    public function createResponseLogPlugin()
    {
        return new ZedResponseLogPlugin();
    }

    /**
     * @return \Spryker\Yves\ZedRequest\Plugin\ZedRequestHeaderMiddleware
     */
    public function createZedRequestHeaderMiddleware()
    {
        return new ZedRequestHeaderMiddleware($this->getUtilNetworkService());
    }

    /**
     * @return \Spryker\Yves\ZedRequest\Dependency\Service\ZedRequestToUtilNetworkInterface
     */
    protected function getUtilNetworkService()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_UTIL_NETWORK);
    }

    /**
     * @return \Spryker\Yves\ZedRequest\HealthCheck\HealthCheckInterface
     */
    public function createZedRequestHealthChecker(): HealthCheckInterface
    {
        return new ZedRequestHealthCheck(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function createRedisDataCollector(): DataCollectorInterface
    {
        return new ZedRequestDataCollector(
            $this->createZedRequestLogger()
        );
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface
     */
    public function createZedRequestLogger(): ZedRequestLoggerInterface
    {
        return new ZedRequestInMemoryLogger(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ZedRequestToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
