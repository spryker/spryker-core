<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent;

use Spryker\Client\Agent\Dependency\Client\AgentToSessionClientBridge;
use Spryker\Client\Agent\Dependency\Client\AgentToSessionClientInterface;
use Spryker\Client\Agent\Dependency\Client\AgentToZedRequestClientBridge;
use Spryker\Client\Agent\Dependency\Client\AgentToZedRequestClientInterface;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class AgentDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const PLUGINS_CUSTOMER_IMPERSONATION_SANITIZER = 'PLUGINS_CUSTOMER_IMPERSONATION_SANITIZER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addZedRequestClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addCustomerImpersonationSanitizerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container): AgentToZedRequestClientInterface {
            return new AgentToZedRequestClientBridge(
                $container->getLocator()->zedRequest()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container): AgentToSessionClientInterface {
            return new AgentToSessionClientBridge(
                $container->getLocator()->session()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerImpersonationSanitizerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CUSTOMER_IMPERSONATION_SANITIZER, function () {
            return $this->getCustomerImpersonationSanitizerPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\AgentExtension\Dependency\Plugin\CustomerImpersonationSanitizerPluginInterface[]
     */
    protected function getCustomerImpersonationSanitizerPlugins(): array
    {
        return [];
    }
}
