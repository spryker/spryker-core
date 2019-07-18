<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 */
class BaseDiscountPromotionSaverPlugin extends AbstractPlugin
{
    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return bool
     */
    protected function isDiscountWithPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer): bool
    {
        return $discountConfiguratorTransfer->getDiscountCalculator()->getCollectorStrategyType() === DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    protected function getDiscountPromotionTransfer(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountPromotionTransfer
    {
        $discountGeneralTransfer = $discountConfiguratorTransfer->getDiscountGeneral();
        $discountGeneralTransfer->requireIdDiscount();
        $discountPromotionTransfer = $discountConfiguratorTransfer->getDiscountCalculator()->getDiscountPromotion() ?? new DiscountPromotionTransfer();
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        return $discountPromotionTransfer;
    }
}
