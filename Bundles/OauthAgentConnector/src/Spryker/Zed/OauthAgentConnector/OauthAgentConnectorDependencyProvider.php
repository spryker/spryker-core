<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToAgentFacadeBridge;
use Spryker\Zed\OauthAgentConnector\Dependency\Facade\OauthAgentConnectorToOauthFacadeBridge;
use Spryker\Zed\OauthAgentConnector\Dependency\Service\OauthAgentConnectorToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig getConfig()
 */
class OauthAgentConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_AGENT = 'FACADE_AGENT';
    /**
     * @var string
     */
    public const FACADE_OAUTH = 'FACADE_OAUTH';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addAgentFacade($container);
        $container = $this->addOauthFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAgentFacade(Container $container): Container
    {
        $container->set(static::FACADE_AGENT, function (Container $container) {
            return new OauthAgentConnectorToAgentFacadeBridge($container->getLocator()->agent()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthFacade(Container $container): Container
    {
        $container->set(static::FACADE_OAUTH, function (Container $container) {
            return new OauthAgentConnectorToOauthFacadeBridge($container->getLocator()->oauth()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new OauthAgentConnectorToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }
}
