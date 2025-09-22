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

/**
 * @method \Spryker\Client\Agent\AgentConfig getConfig()
 */
class AgentDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const PLUGINS_IMPERSONATION_SESSION_FINISHER = 'PLUGINS_IMPERSONATION_SESSION_FINISHER';

    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addZedRequestClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addCustomerClient($container);

        $container = $this->addImpersonationSessionFinisherPlugins($container);

        return $container;
    }

    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container): AgentToZedRequestClientInterface {
            return new AgentToZedRequestClientBridge(
                $container->getLocator()->zedRequest()->client(),
            );
        });

        return $container;
    }

    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container): AgentToSessionClientInterface {
            return new AgentToSessionClientBridge(
                $container->getLocator()->session()->client(),
            );
        });

        return $container;
    }

    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new AgentToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }

    protected function addImpersonationSessionFinisherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_IMPERSONATION_SESSION_FINISHER, function () {
            return $this->getImpersonationSessionFinisherPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationSessionFinisherPluginInterface>
     */
    protected function getImpersonationSessionFinisherPlugins(): array
    {
        return [];
    }
}
