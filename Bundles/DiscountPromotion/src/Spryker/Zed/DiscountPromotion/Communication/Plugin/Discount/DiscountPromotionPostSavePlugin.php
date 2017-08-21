<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountPostSavePluginInterface;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacade getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 */
class DiscountPromotionPostSavePlugin extends BaseDiscountPromotionSaverPlugin implements DiscountPostSavePluginInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     */
    public function postSave(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        if (!$this->isDiscountWithPromotion($discountConfiguratorTransfer)) {
            return $discountConfiguratorTransfer;
        }

        $discountPromotionTransfer = $this->getDiscountPromotionTransfer($discountConfiguratorTransfer);
        $discountPromotionTransfer = $this->getFacade()->savePromotionDiscount($discountPromotionTransfer);
        $discountConfiguratorTransfer->getDiscountCalculator()->setDiscountPromotion($discountPromotionTransfer);

        return $discountConfiguratorTransfer;
    }

}
