<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui;

use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Client\AgentSecurityMerchantPortalGuiToSessionClientBridge;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToMessengerFacadeBridge;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToRouterFacadeBridge;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToSecurityFacadeBridge;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToUserFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 */
class AgentSecurityMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @var string
     */
    public const FACADE_SECURITY = 'FACADE_SECURITY';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const FACADE_ROUTER = 'FACADE_ROUTER';

    /**
     * @uses \Spryker\Zed\Security\Communication\Loader\Services\AuthorizationCheckerServiceLoader::SERVICE_SECURITY_AUTHORIZATION_CHECKER
     *
     * @var string
     */
    public const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     *
     * @var string
     */
    public const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_AGENT_USER_AUTHENTICATION_HANDLER = 'PLUGINS_MERCHANT_AGENT_USER_AUTHENTICATION_HANDLER';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @uses \Spryker\Zed\ZedUi\Communication\Plugin\Application\ZedUiApplicationPlugin::SERVICE_ZED_UI_FACTORY
     *
     * @var string
     */
    public const SERVICE_ZED_UI_FACTORY = 'SERVICE_ZED_UI_FACTORY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMessengerFacade($container);
        $container = $this->addSecurityFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addUserFacade($container);
        $container = $this->addRouterFacade($container);
        $container = $this->addAuthorizationCheckerService($container);
        $container = $this->addTokenStorageService($container);
        $container = $this->addMerchantAgentUserAuthenticationHandlerPlugins($container);
        $container = $this->addSessionClient($container);
        $container = $this->addZedUiFactory($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            return new AgentSecurityMerchantPortalGuiToMessengerFacadeBridge(
                $container->getLocator()->messenger()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSecurityFacade(Container $container): Container
    {
        $container->set(static::FACADE_SECURITY, function (Container $container) {
            return new AgentSecurityMerchantPortalGuiToSecurityFacadeBridge(
                $container->getLocator()->security()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new AgentSecurityMerchantPortalGuiToMerchantUserFacadeBridge(
                $container->getLocator()->merchantUser()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new AgentSecurityMerchantPortalGuiToUserFacadeBridge(
                $container->getLocator()->user()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRouterFacade(Container $container): Container
    {
        $container->set(static::FACADE_ROUTER, function (Container $container) {
            return new AgentSecurityMerchantPortalGuiToRouterFacadeBridge(
                $container->getLocator()->router()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAuthorizationCheckerService(Container $container): Container
    {
        $container->set(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTokenStorageService(Container $container): Container
    {
        $container->set(static::SERVICE_SECURITY_TOKEN_STORAGE, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_SECURITY_TOKEN_STORAGE);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantAgentUserAuthenticationHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_AGENT_USER_AUTHENTICATION_HANDLER, function () {
            return $this->getMerchantAgentUserAuthenticationHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface>
     */
    protected function getMerchantAgentUserAuthenticationHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return new AgentSecurityMerchantPortalGuiToSessionClientBridge(
                $container->getLocator()->session()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addZedUiFactory(Container $container): Container
    {
        $container->set(static::SERVICE_ZED_UI_FACTORY, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_ZED_UI_FACTORY);
        });

        return $container;
    }
}
