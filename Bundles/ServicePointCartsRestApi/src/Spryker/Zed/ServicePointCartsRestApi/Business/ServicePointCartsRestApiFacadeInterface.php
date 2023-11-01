<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCartsRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

interface ServicePointCartsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Replaces quote items using applicable strategy if shipments are provided.
     * - Returns original `QuoteTransfer` in case `RestCheckoutRequestAttributesTransfer.shipments` and `RestCheckoutRequestAttributesTransfer.shipment` are not provided.
     * - Returns replaced and recalculated quote items if a replacement strategy executed successfully.
     * - Returns the result of a replacement strategy without recalculation if the replacement strategy executed with any error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function replaceServicePointQuoteItems(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer;
}
