<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion;

use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityBridge;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleBridge;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DiscountPromotionDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';
    const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new DiscountPromotionToProductBridge($container->getLocator()->product()->facade());
        };

        $container[static::FACADE_AVAILABILITY] = function (Container $container) {
           return new DiscountPromotionToAvailabilityBridge($container->getLocator()->availability()->facade());
        };

        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new DiscountPromotionToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

}
