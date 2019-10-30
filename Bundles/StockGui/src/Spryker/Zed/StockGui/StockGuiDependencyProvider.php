<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui;

use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class StockGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_STOCK = 'PROPEL_QUERY_STOCK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addStockPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STOCK, function () {
            return SpyStockQuery::create();
        });

        return $container;
    }
}
