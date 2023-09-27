<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointsRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

interface ServicePointsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Requires `RestCheckoutDataTransfer.quote` to be provided.
     * - Does nothing `RestCheckoutDataTransfer.quote.items` are not provided.
     * - Expects `RestCheckoutDataTransfer.quote.items.servicePoint.uuid` to be provided.
     * - Extracts `RestCheckoutDataTransfer.quote.items.servicePoint`.
     * - Expands `RestCheckoutDataTransfer` with extracted service points.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function expandCheckoutDataWithAvailableServicePoints(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataTransfer;

    /**
     * Specification:
     * - Does nothing if `RestCheckoutRequestAttributesTransfer.servicePoints` is not provided.
     * - Requires `QuoteTransfer.store.name` and `RestCheckoutRequestAttributesTransfer.servicePoints.idServicePoint` to be provided.
     * - Gets service points collection by `RestCheckoutRequestAttributesTransfer.servicePoints.idServicePoint`.
     * - Maps found filtered `ServicePointTransfers` to `QuoteTransfer.items.servicePoint`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapServicePointToQuoteItem(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer;
}
