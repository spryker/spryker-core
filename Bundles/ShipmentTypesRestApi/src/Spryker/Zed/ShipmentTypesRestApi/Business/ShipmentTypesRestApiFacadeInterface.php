<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentTypesRestApiFacadeInterface
{
    /**
     * Specification:
     * - Does nothing if `Shipment.method` is empty for each element in `QuoteTransfer.items`.
     * - Expects QuoteTransfer.items.shipment.method.idShipmentMethod to be set.
     * - Gets available shipment methods for the provided quote.
     * - Expands items with shipment types taken from shipment methods to `Quote.items.shipmentType`.
     * - Expands `Quote.items.shipment.shipmentTypeUuid` from `Quote.items.shipmentType.uuid`.
     * - Expands `Quote.expenses.shipment.shipmentTypeUuid` from `Quote.expenses.shipment.method.shipmentType.uuid`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Does nothing if `idShipmentMethod` transfer property is empty for each element in `CheckoutDataTransfer.shipments` in case of multi shipment delivery.
     * - Does nothing if `CheckoutDataTransfer.shipment.idShipmentMethod` is empty in case of single shipment delivery.
     * - Requires `CheckoutDataTransfer.quote.store.idStore` transfer property to be set.
     * - Expects `CheckoutDataTransfer.shipments.shipmentMethod.idShipmentMethod` to be set in case of multi shipment delivery.
     * - Gets available shipment methods.
     * - Validates whether shipment type related to the shipment method is active and belongs to the quote store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateShipmentTypeCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer;
}
