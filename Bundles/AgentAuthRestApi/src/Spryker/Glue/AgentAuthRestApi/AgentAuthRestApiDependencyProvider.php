<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi;

use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToAgentClientBridge;
use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientBridge;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToOauthServiceBridge;
use Spryker\Glue\AgentAuthRestApi\Dependency\Service\AgentAuthRestApiToUtilEncodingServiceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig getConfig()
 */
class AgentAuthRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AGENT = 'CLIENT_AGENT';
    public const CLIENT_OAUTH = 'CLIENT_OAUTH';

    public const SERVICE_OAUTH = 'SERVICE_OAUTH';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addAgentClient($container);
        $container = $this->addOauthClient($container);
        $container = $this->addOauthService($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addAgentClient(Container $container): Container
    {
        $container->set(static::CLIENT_AGENT, function (Container $container) {
            return new AgentAuthRestApiToAgentClientBridge(
                $container->getLocator()->agent()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOauthClient(Container $container): Container
    {
        $container->set(static::CLIENT_OAUTH, function (Container $container) {
            return new AgentAuthRestApiToOauthClientBridge(
                $container->getLocator()->oauth()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOauthService(Container $container): Container
    {
        $container->set(static::SERVICE_OAUTH, function (Container $container) {
            return new AgentAuthRestApiToOauthServiceBridge(
                $container->getLocator()->oauth()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new AgentAuthRestApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }
}
