<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 */
class DiscountPromotionPostCreatePlugin extends BaseDiscountPromotionSaverPlugin implements DiscountPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function postCreate(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        if (!$this->isDiscountWithPromotion($discountConfiguratorTransfer)) {
            return $discountConfiguratorTransfer;
        }

        $discountPromotionTransfer = $this->getDiscountPromotionTransfer($discountConfiguratorTransfer);
        $discountPromotionTransfer = $this->getFacade()->createPromotionDiscount($discountPromotionTransfer);
        $discountConfiguratorTransfer->getDiscountCalculator()->setDiscountPromotion($discountPromotionTransfer);

        return $discountConfiguratorTransfer;
    }
}
