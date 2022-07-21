<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 */
class DiscountPromotionDiscountPostUpdatePlugin extends BaseDiscountPromotionSaverPlugin implements DiscountPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `DiscountConfigurator.discountCalculator` transfer property to be set.
     * - Requires `DiscountConfigurator.discountGeneral.idDiscount` transfer property to be set.
     * - Checks if discount is promotional.
     * - Sets an empty promotional discount to `DiscountConfigurator.discountCalculator`, if `DiscountConfigurator.discountCalculator.discountPromotion` is not set.
     * - Otherwise, checks if `DiscountConfigurator.discountCalculator.discountPromotion.idDiscountPromotion` is set.
     * - If `DiscountConfigurator.discountCalculator.discountPromotion.idDiscountPromotion` is set, updates promotional discount.
     * - Otherwise, creates a new promotional discount.
     * - Sets promotional discount to `DiscountConfigurator.discountCalculator`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function postUpdate(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorTransfer
    {
        if (!$this->isDiscountWithPromotion($discountConfiguratorTransfer)) {
            return $discountConfiguratorTransfer;
        }

        return $this->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);
    }
}
