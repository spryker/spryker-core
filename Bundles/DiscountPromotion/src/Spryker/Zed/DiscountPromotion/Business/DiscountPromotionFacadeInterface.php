<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountPromotionFacadeInterface
{
    /**
     * Specification:
     *  - Collects discountable items when promotion discount is used.
     *  - If item is not in quote then it adds it this quote::promotionItems, if its already there then it would return
     *    this item to discount module for discount calculation
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Persist discount promotion
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function createPromotionDiscount(DiscountPromotionTransfer $discountPromotionTransfer);

    /**
     * Specification:
     *  - Update discount promotion
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function updatePromotionDiscount(DiscountPromotionTransfer $discountPromotionTransfer);

    /**
     * Specification:
     *  - Removes discount promotion from persistence by given discount id if exists.
     *
     * @api
     *
     * @param int $idDiscount
     *
     * @return void
     */
    public function removePromotionByIdDiscount(int $idDiscount): void;

    /**
     * Specification:
     *  - Read discount promotion from persistence by given promotion id
     *
     * @api
     *
     * @param int $idDiscountPromotion
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscountPromotion($idDiscountPromotion);

    /**
     * Specification:
     *  - Expand DiscountConfigurationTransfer with DiscountPromotion data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function expandDiscountConfigurationWithPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer);

    /**
     * Specification:
     *  - Check if given discount have promotion discounts.
     *
     * @api
     *
     * @param int $idDiscount
     *
     * @return bool
     */
    public function isDiscountWithPromotion($idDiscount);

    /**
     * Specification:
     *  - Read discount promotion from persistence by given primary id
     *
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscount($idDiscount);
}
