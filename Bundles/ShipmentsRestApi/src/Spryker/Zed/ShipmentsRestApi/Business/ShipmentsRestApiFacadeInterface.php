<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApi\Business;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

interface ShipmentsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Maps rest request shipment to quote.
     * - Finds shipment method by shipmentSelection from request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapShipmentToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer;

    /**
     * Specification:
     * - Verifies if shipment method is valid.
     * - Returns CheckoutResponseTransfer with error if any check was failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateShipmentMethodCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer;

    /**
     * Specification:
     * - Maps `RestCheckoutRequestAttributesTransfer.shipments` to `QuoteTransfer.items[*].shipment`.
     * - Maps `RestCheckoutRequestAttributesTransfer.shipments` to `QuoteTransfer.bundleItems[*].shipment`.
     * - Expands quote expenses with shipment expenses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapShipmentsToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer;

    /**
     * Specification:
     * - Expands `RestCheckoutDataTransfer` with available shipment methods.
     * - Expects `RestCheckoutDataTransfer.Quote` to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function expandCheckoutDataWithAvailableShipmentMethods(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataTransfer;

    /**
     * Specification:
     * - Expects `checkoutDataTransfer.shipments` to be provided.
     * - Requires `checkoutDataTransfer.quote` to be set.
     * - Validates if `CheckoutDataTransfer` provides shipment data per item level.
     * - Validates if `CheckoutDataTransfer` provides shipment data per bundle item level.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateItemsInCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer;
}
