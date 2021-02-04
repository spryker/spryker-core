<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationResponseTransfer;
use Generated\Shared\Transfer\SingleMerchantWishlistItemsValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface MerchantSwitcherFacadeInterface
{
    /**
     * Specification:
     * - Requires MerchantSwitchRequestTransfer.merchantReference.
     * - Requires MerchantSwitchRequestTransfer.quote.
     * - Sets QuoteTransfer.merchantReference with value from MerchantSwitchRequestTransfer.merchantReference.
     * - Updates a quote in the database if a storage strategy is `database`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuote(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer;

    /**
     * Specification:
     * - Finds product offer substitution for items in cart depending on the provided merchant reference.
     * - Changes ItemTransfer.productOfferReference to reference of the product offer from merchant MerchantSwitcherRequestTransfer.merchantReference.
     * - Changes ItemTransfer.merchantReference to the value of MerchantSwitchRequestTransfer.merchantReference.
     * - Requires MerchantSwitchRequestTransfer.quote.
     * - Requires MerchantSwitchRequestTransfer.merchantReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuoteItems(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer;

    /**
     * Specification:
     * - Validates that all items in the quote have requested merchant reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer $singleMerchantQuoteValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SingleMerchantQuoteValidationResponseTransfer
     */
    public function validateMerchantInQuoteItems(
        SingleMerchantQuoteValidationRequestTransfer $singleMerchantQuoteValidationRequestTransfer
    ): SingleMerchantQuoteValidationResponseTransfer;

    /**
     * Specification:
     * - Finds product offer substitution for items in wishlist depending on the provided merchant reference.
     * - Changes `WishlistItem::productOfferReference` transfer property to reference of the product offer from merchant `MerchantSwitcherRequestTransfer::merchantReference`.
     * - Changes WishlistItem.merchantReference transfer property to the value of MerchantSwitchRequestTransfer.merchantReference.
     * - Requires MerchantSwitchRequestTransfer.wishlist.
     * - Requires MerchantSwitchRequestTransfer.merchantReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInWishlistItems(
        MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
    ): MerchantSwitchResponseTransfer;

    /**
     * Specification:
     * - Validates that all items in the wishlist have the requested merchant reference.
     * - Returns `SingleMerchantWishlistItemsValidationResponse` transfer object that contains a list of errors in case of a failed validation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\SingleMerchantWishlistItemsValidationResponseTransfer
     */
    public function validateWishlistItems(WishlistTransfer $wishlistTransfer): SingleMerchantWishlistItemsValidationResponseTransfer;
}
