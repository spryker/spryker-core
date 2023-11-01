<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipmentsRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;

interface MerchantShipmentsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Expects `QuoteTransfer.items.shipment` to be set.
     * - Expands `QuoteTransfer.items.shipment` with `QuoteTransfer.items.merchantReference`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteShipmentWithMerchantReference(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
