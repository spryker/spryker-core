<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container as BackendContainer;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientBridge;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientBridge;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeBridge;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeBridge;

/**
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MultiFactorAuthDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_MULTI_FACTOR_AUTH = 'CLIENT_MULTI_FACTOR_AUTH';

    /**
     * @var string
     */
    public const FACADE_MULTI_FACTOR_AUTH = 'FACADE_MULTI_FACTOR_AUTH';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH = 'PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH';

    /**
     * @var string
     */
    public const PLUGINS_USER_MULTI_FACTOR_AUTH = 'PLUGINS_USER_MULTI_FACTOR_AUTH';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addMultiFactorAuthClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCustomerMultiFactorAuthPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(BackendContainer $container): BackendContainer
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addUserFacade($container);
        $container = $this->addUserMultiFactorAuthPlugins($container);
        $container = $this->addMultiFactorAuthFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addMultiFactorAuthClient(Container $container): Container
    {
        $container->set(static::CLIENT_MULTI_FACTOR_AUTH, function (Container $container) {
            return new MultiFactorAuthToMultiFactorAuthClientBridge(
                $container->getLocator()->multiFactorAuth()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
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
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
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
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addMultiFactorAuthFacade(BackendContainer $container): BackendContainer
    {
        $container->set(static::FACADE_MULTI_FACTOR_AUTH, function (BackendContainer $container) {
            return new MultiFactorAuthToMultiFactorAuthFacadeBridge(
                $container->getLocator()->multiFactorAuth()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addUserFacade(BackendContainer $container): BackendContainer
    {
        $container->set(static::FACADE_USER, function (BackendContainer $container) {
            return new MultiFactorAuthToUserFacadeBridge(
                $container->getLocator()->user()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addUserMultiFactorAuthPlugins(BackendContainer $container): BackendContainer
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
}
