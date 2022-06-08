<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionCollectionTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountPromotionFacadeInterface
{
    /**
     * Specification:
     * - Collects discountable items for multiple abstract SKUs, if `spy_discount_promotion.abstract_skus` field is present at the DB.
     * - Otherwise, collects discountable items for single abstract SKU.
     * - If item(s) is not in quote, adds it to `QuoteTransfer::promotionItems`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
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
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface::getDiscountPromotionCollection()} instead.
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
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface::getDiscountPromotionCollection()} instead.
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscount($idDiscount);

    /**
     * Specification:
     *  - Retrieves discount promotion from persistence by given UUID.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface::getDiscountPromotionCollection()} instead.
     *
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByUuid(string $uuid): ?DiscountPromotionTransfer;

    /**
     * Specification:
     * - If cart change operation is 'add', then validates cart items discount promotions on availability for the current cart.
     * - Returns pre-check transfer with error messages in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartDiscountPromotions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Retrieves discount promotions from persistence by criteria.
     *
     * @api
     *
     * {@internal DiscountPromotionCriteriaTransfer.discountPromotionConditions.uuids will work if uuid field exists in database table.}
     *
     * @param \Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionCollectionTransfer
     */
    public function getDiscountPromotionCollection(DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer): DiscountPromotionCollectionTransfer;

    /**
     * Specification:
     * - Filters discount promotion items from `CartChangeTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterDiscountPromotionItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Checks if the voucher code is added to the collection of used non-applied codes.
     * - Returns `DiscountVoucherCheckResponseTransfer::isSuccessful` equal to `true` with a success message, if the voucher code is added.
     * - Checks if the voucher code is added to the collection of voucher discounts otherwise.
     * - Returns `DiscountVoucherCheckResponseTransfer::isSuccessful` equal to `true` with a success message, if the voucher code is added.
     * - Returns `DiscountVoucherCheckResponseTransfer::isSuccessful` equal to `false` with an error message otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer
     */
    public function checkVoucherCodeApplied(
        QuoteTransfer $quoteTransfer,
        string $voucherCode
    ): DiscountVoucherCheckResponseTransfer;
}
