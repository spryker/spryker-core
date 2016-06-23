<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryBridge;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToProductBridge;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToSalesAggregatorBridge;

class RatepayDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_SALES_AGGREGATOR = 'FACADE_SALES_AGGREGATED';
    const FACADE_GLOSSARY = 'GLOSSARY_FACADE';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
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
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new RatepayToProductBridge($container->getLocator()->product()->facade());
        };
        
        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new RatepayToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

}
