<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryBridge;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyBridge;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToProductBridge;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToSalesAggregatorBridge;

class RatepayDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SALES_AGGREGATOR = 'FACADE_SALES_AGGREGATED';
    public const FACADE_GLOSSARY = 'GLOSSARY_FACADE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addSalesAggregatorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addMoneyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesAggregatorFacade(Container $container)
    {
        $container[static::FACADE_SALES_AGGREGATOR] = function (Container $container) {
            return new RatepayToSalesAggregatorBridge($container->getLocator()->salesAggregator()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new RatepayToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container)
    {
        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new RatepayToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container)
    {
        $container[self::FACADE_MONEY] = function (Container $container) {
            return new RatepayToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }
}
