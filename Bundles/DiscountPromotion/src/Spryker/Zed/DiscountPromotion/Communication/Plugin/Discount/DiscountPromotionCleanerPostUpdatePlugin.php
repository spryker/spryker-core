<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 */
class DiscountPromotionCleanerPostUpdatePlugin extends BaseDiscountPromotionSaverPlugin implements DiscountPostUpdatePluginInterface
{
    /**
     * {@inheritdoc}
     *  - Checks if given discount CollectorStrategyType is not set to "promotion"
     *    then removes Promotion from Discount.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     */
    public function postUpdate(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        if (!$this->isDiscountWithPromotion($discountConfiguratorTransfer)) {
            $this->getFacade()->removePromotionByIdDiscount($discountConfiguratorTransfer->getDiscountGeneral()->getIdDiscount());
            $discountConfiguratorTransfer->getDiscountCalculator()->setDiscountPromotion(new DiscountPromotionTransfer());
        }

        return $discountConfiguratorTransfer;
    }
}
