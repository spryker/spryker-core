<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\MultiFactorAuth\Dependency\Client\MultiFactorAuthToAgentClientBridge;
use Spryker\Yves\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientBridge;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MultiFactorAuthDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_AGENT = 'CLIENT_AGENT';

    /**
     * @var string
     */
    public const PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH = 'PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH';

    /**
     * @var string
     */
    public const PLUGINS_AGENT_MULTI_FACTOR_AUTH = 'PLUGINS_AGENT_MULTI_FACTOR_AUTH';

    /**
     * @var string
     */
    public const PLUGINS_POST_LOGIN_MULTI_FACTOR_AUTH = 'PLUGINS_POST_LOGIN_MULTI_FACTOR_AUTH';

    /**
     * @var string
     */
    public const SERVICE_TRANSLATOR = 'translator';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @uses \Spryker\Yves\Form\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     *
     * @var string
     */
    public const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @var string
     */
    public const TWIG_ENVIRONMENT = 'TWIG_ENVIRONMENT';

    /**
     * @uses \Spryker\Yves\Twig\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCustomerClient($container);
        $container = $this->addAgentClient($container);
        $container = $this->addCustomerMultiFactorAuthPlugins($container);
        $container = $this->addAgentMultiFactorAuthPlugins($container);
        $container = $this->addPostLoginMultiFactorAuthenticationPlugins($container);
        $container = $this->addTranslatorService($container);
        $container = $this->addRequestStackService($container);
        $container = $this->addTwigService($container);
        $container = $this->addCsrfTokenManager($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new MultiFactorAuthToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAgentClient(Container $container): Container
    {
        $container->set(static::CLIENT_AGENT, function (Container $container) {
            return new MultiFactorAuthToAgentClientBridge(
                $container->getLocator()->agent()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addTranslatorService(Container $container): Container
    {
        $container->set(static::SERVICE_TRANSLATOR, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TRANSLATOR);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerMultiFactorAuthPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH, function () {
            return $this->getCustomerMultiFactorAuthPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    protected function getCustomerMultiFactorAuthPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAgentMultiFactorAuthPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_AGENT_MULTI_FACTOR_AUTH, function () {
            return $this->getAgentMultiFactorAuthPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPostLoginMultiFactorAuthenticationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_LOGIN_MULTI_FACTOR_AUTH, function () {
            return $this->getPostLoginMultiFactorAuthenticationPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    protected function getAgentMultiFactorAuthPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface>
     */
    protected function getPostLoginMultiFactorAuthenticationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStackService(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigService(Container $container): Container
    {
        $container->set(static::TWIG_ENVIRONMENT, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCsrfTokenManager(Container $container): Container
    {
        $container->set(static::SERVICE_FORM_CSRF_PROVIDER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_FORM_CSRF_PROVIDER);
        });

        return $container;
    }
}
