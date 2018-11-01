<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\CartNote\Communication\Plugin\QuoteItemFinderPlugin;
use Spryker\Zed\CartNote\Dependency\Facade\CartNoteToQuoteFacadeBridge;
use Spryker\Zed\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CartNoteDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SALES_ORDER_QUERY = 'SALES_ORDER_QUERY';
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const PLUGIN_QUOTE_ITEMS_FINDER = 'PLUGIN_QUOTE_ITEMS_FINDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addQuoteFacade($container);
        $container = $this->addQuoteItemsFinderPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addSalesOrderQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderQuery(Container $container): Container
    {
        $container[static::SALES_ORDER_QUERY] = function (Container $container) {
            return SpySalesOrderQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container): Container
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return new CartNoteToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteItemsFinderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_ITEMS_FINDER] = function (Container $container) {
            return $this->getQuoteItemsFinderPlugin();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected function getQuoteItemsFinderPlugin(): QuoteItemFinderPluginInterface
    {
        return new QuoteItemFinderPlugin();
    }
}
