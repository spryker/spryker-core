<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToSessionClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    /**
     * @deprecated Will be removed in the next major.
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @deprecated Will be removed in the next major.
     */
    public const PLUGINS_CUSTOMER_POST_REGISTER = 'PLUGINS_CUSTOMER_POST_REGISTER';

    public const PLUGINS_CUSTOMER_POST_CREATE = 'PLUGINS_CUSTOMER_POST_CREATE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addCustomerPostRegisterPlugins($container);
        $container = $this->addCustomerPostCreatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new CustomersRestApiToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @deprecated Will be removed in the next major.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return new CustomersRestApiToSessionClientBridge($container->getLocator()->session()->client());
        };

        return $container;
    }

    /**
     * @deprecated use addCustomerPostCreatePlugins()
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCustomerPostRegisterPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CUSTOMER_POST_REGISTER] = function () {
            return $this->getCustomerPostRegisterPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCustomerPostCreatePlugins(Container $container): Container
    {
        $container[static::PLUGINS_CUSTOMER_POST_CREATE] = function () {
            return $this->getCustomerPostCreatePlugins();
        };

        return $container;
    }

    /**
     * @deprecated use getCustomerPostCreatePlugins()
     *
     * @return \Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostRegisterPluginInterface[]
     */
    protected function getCustomerPostRegisterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostCreatePluginInterface[]
     */
    protected function getCustomerPostCreatePlugins(): array
    {
        return [];
    }
}
