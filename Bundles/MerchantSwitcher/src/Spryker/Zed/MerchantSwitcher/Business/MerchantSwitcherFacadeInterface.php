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
}
