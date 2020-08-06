<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotionsRestApi;

use Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\DiscountPromotionsRestApi\DiscountPromotionsRestApiConfig getConfig()
 */
class DiscountPromotionsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_DISCOUNT_PROMOTION = 'FACADE_DISCOUNT_PROMOTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addDiscountPromotionFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDiscountPromotionFacade(Container $container): Container
    {
        $container->set(static::FACADE_DISCOUNT_PROMOTION, function (Container $container) {
            return new DiscountPromotionsRestApiToDiscountPromotionFacadeBridge($container->getLocator()->discountPromotion()->facade());
        });

        return $container;
    }
}
