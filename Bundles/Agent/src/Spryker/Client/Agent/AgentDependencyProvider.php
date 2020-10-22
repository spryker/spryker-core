<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent;

use Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientBridge;
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
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    public const PLUGINS_IMPERSONATION_FINISHER = 'PLUGINS_IMPERSONATION_FINISHER';

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
        $container = $this->addCustomerClient($container);

        $container = $this->addImpersonationFinisherPlugins($container);

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
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new AgentToCustomerClientBridge(
                $container->getLocator()->customer()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addImpersonationFinisherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_IMPERSONATION_FINISHER, function () {
            return $this->getImpersonationFinisherPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationFinisherPluginInterface[]
     */
    protected function getImpersonationFinisherPlugins(): array
    {
        return [];
    }
}
