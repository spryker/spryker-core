<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;

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
     * - Changes ItemTransfer.productOfferReference to the value from the substitution merchant reference.
     * - Changes ItemTransfer.merchantReference property to the value from the substitution product offer reference.
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
}
