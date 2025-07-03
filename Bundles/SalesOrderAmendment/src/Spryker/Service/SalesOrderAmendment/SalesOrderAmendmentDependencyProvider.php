<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesOrderAmendment;

use Spryker\Service\Container\Container;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;

class SalesOrderAmendmentDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_ORIGINAL_SALES_ORDER_ITEM_GROUP_KEY_EXPANDER = 'PLUGINS_ORIGINAL_SALES_ORDER_ITEM_GROUP_KEY_EXPANDER';

    /**
     * @param \Spryker\Service\Container\Container $container
     *
     * @return \Spryker\Service\Container\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = $this->addOriginalSalesOrderItemsGroupKeyExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\Container $container
     *
     * @return \Spryker\Service\Container\Container
     */
    protected function addOriginalSalesOrderItemsGroupKeyExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORIGINAL_SALES_ORDER_ITEM_GROUP_KEY_EXPANDER, function () {
            return $this->getOriginalSalesOrderItemGroupKeyExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Service\SalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemGroupKeyExpanderPluginInterface>
     */
    protected function getOriginalSalesOrderItemGroupKeyExpanderPlugins(): array
    {
        return [];
    }
}
