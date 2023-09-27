<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyAuthorizationConnector\Business;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Zed\ApiKeyAuthorizationConnector\Business\Authorizer\ApiKeyAuthorizer;
use Spryker\Zed\ApiKeyAuthorizationConnector\Business\Authorizer\ApiKeyAuthorizerInterface;
use Spryker\Zed\ApiKeyAuthorizationConnector\Business\Logger\ApiKeyAuthorizationLogger;
use Spryker\Zed\ApiKeyAuthorizationConnector\Business\Logger\ApiKeyAuthorizationLoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig getConfig()
 */
class ApiKeyAuthorizationConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var string
     */
    protected const LOGGER_NAME = 'apiKeyAuthorizationLogger';

    /**
     * @return \Spryker\Zed\ApiKeyAuthorizationConnector\Business\Authorizer\ApiKeyAuthorizerInterface
     */
    public function createApiKeyAuthorizer(): ApiKeyAuthorizerInterface
    {
        return new ApiKeyAuthorizer(
            $this->getConfig(),
            $this->createApiKeyAuthorizationLogger(),
        );
    }

    /**
     * @return \Spryker\Zed\ApiKeyAuthorizationConnector\Business\Logger\ApiKeyAuthorizationLoggerInterface
     */
    public function createApiKeyAuthorizationLogger(): ApiKeyAuthorizationLoggerInterface
    {
        return new ApiKeyAuthorizationLogger($this->createLogger());
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    public function createLogger(): ?LoggerInterface
    {
        if (!$this->getConfig()->isLoggingEnabled()) {
            return null;
        }

        return new Logger(static::LOGGER_NAME, [
            $this->createBufferedStreamHandler(),
        ]);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createBufferedStreamHandler(): HandlerInterface
    {
        return new BufferHandler(
            $this->createStreamHandler(),
        );
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createStreamHandler(): HandlerInterface
    {
        return new StreamHandler($this->getConfig()->getLogFilepath());
    }
}
