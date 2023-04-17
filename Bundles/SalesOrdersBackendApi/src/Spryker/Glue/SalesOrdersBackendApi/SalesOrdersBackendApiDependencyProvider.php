<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\SalesOrdersBackendApi\Dependency\Facade\SalesOrdersBackendApiToSalesFacadeBridge;

/**
 * @method \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiConfig getConfig()
 */
class SalesOrdersBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const PLUGINS_API_ORDERS_ATTRIBUTES_MAPPER = 'PLUGINS_API_ORDERS_ATTRIBUTES_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);

        $container = $this->addSalesFacade($container);
        $container = $this->addApiOrdersAttributesMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new SalesOrdersBackendApiToSalesFacadeBridge(
                $container->getLocator()->sales()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addApiOrdersAttributesMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_API_ORDERS_ATTRIBUTES_MAPPER, function () {
            return $this->getApiOrdersAttributesMapperPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\ApiOrdersAttributesMapperPluginInterface>
     */
    protected function getApiOrdersAttributesMapperPlugins(): array
    {
        return [];
    }
}
