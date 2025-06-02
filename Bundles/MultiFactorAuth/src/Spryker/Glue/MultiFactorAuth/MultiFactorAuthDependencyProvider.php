<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientBridge;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientBridge;

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
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH = 'PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH';

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
}
