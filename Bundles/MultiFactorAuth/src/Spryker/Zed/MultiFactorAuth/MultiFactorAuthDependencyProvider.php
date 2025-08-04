<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MultiFactorAuth\Dependency\Client\MultiFactorAuthToSessionClientBridge;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToCustomerFacadeBridge;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeBridge;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeBridge;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeBridge;

/**
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MultiFactorAuthDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @var string
     */
    public const FACADE_MAIL = 'FACADE_MAIL';

    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const PLUGINS_USER_MULTI_FACTOR_AUTH = 'PLUGINS_USER_MULTI_FACTOR_AUTH';

    /**
     * @var string
     */
    public const PLUGINS_CUSTOMER_SEND_STRATEGY = 'PLUGINS_CUSTOMER_SEND_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_USER_SEND_STRATEGY = 'PLUGINS_USER_SEND_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_MULTI_FACTOR_AUTH_PLUGIN_EXPANDER = 'PLUGINS_MULTI_FACTOR_AUTH_PLUGIN_EXPANDER';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     *
     * @var string
     */
    public const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @var string
     */
    public const PLUGINS_POST_LOGIN_MULTI_FACTOR_AUTH = 'PLUGINS_POST_LOGIN_MULTI_FACTOR_AUTH';

    /**
     * @var string
     */
    public const SERVICE_TRANSLATOR = 'translator';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var string
     */
    public const TWIG_ENVIRONMENT = 'TWIG_ENVIRONMENT';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addGlossaryFacade($container);
        $container = $this->addCustomerSendStrategyPlugins($container);
        $container = $this->addUserSendStrategyPlugins($container);
        $container = $this->addUserMultiFactorAuthPlugins($container);
        $container = $this->addMultiFactorAuthPluginExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addUserFacade($container);
        $container = $this->addUserMultiFactorAuthPlugins($container);
        $container = $this->addPostLoginMultiFactorAuthenticationPlugins($container);
        $container = $this->addMailFacade($container);
        $container = $this->addCsrfProviderService($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addTranslatorService($container);
        $container = $this->addRequestStackService($container);
        $container = $this->addTwigService($container);
        $container = $this->addSessionClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new MultiFactorAuthToMailFacadeBridge(
                $container->getLocator()->mail()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new MultiFactorAuthToGlossaryFacadeBridge(
                $container->getLocator()->glossary()->facade(),
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
            return new MultiFactorAuthToUserFacadeBridge(
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
    protected function addUserMultiFactorAuthPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_MULTI_FACTOR_AUTH, function () {
            return $this->getUserMultiFactorAuthPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    protected function getUserMultiFactorAuthPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostLoginMultiFactorAuthenticationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_LOGIN_MULTI_FACTOR_AUTH, function () {
            return $this->getPostLoginMultiFactorAuthenticationPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface>
     */
    protected function getPostLoginMultiFactorAuthenticationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerSendStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CUSTOMER_SEND_STRATEGY, function () {
            return $this->getCustomerSendStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\SendStrategyPluginInterface>
     */
    protected function getCustomerSendStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserSendStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_SEND_STRATEGY, function () {
            return $this->getUserSendStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\SendStrategyPluginInterface>
     */
    protected function getUserSendStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCsrfProviderService(Container $container): Container
    {
        $container->set(static::SERVICE_FORM_CSRF_PROVIDER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_FORM_CSRF_PROVIDER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new MultiFactorAuthToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addTranslatorService(Container $container): Container
    {
        $container->set(static::SERVICE_TRANSLATOR, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TRANSLATOR);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRequestStackService(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigService(Container $container): Container
    {
        $container->set(static::TWIG_ENVIRONMENT, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return new MultiFactorAuthToSessionClientBridge(
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
    protected function addMultiFactorAuthPluginExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MULTI_FACTOR_AUTH_PLUGIN_EXPANDER, function () {
            return $this->getMultiFactorAuthPluginExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginExpanderPluginInterface>
     */
    protected function getMultiFactorAuthPluginExpanderPlugins(): array
    {
        return [];
    }
}
