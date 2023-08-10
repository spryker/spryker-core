<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentTypeCartFacadeInterface
{
    /**
     * Specification:
     * - Requires `CartChange.items.shipment` transfer property to be set.
     * - Expects `CartChange.items.shipmentType.uuid` transfer property to be set.
     * - Does nothing if `CartChange.items.shipmentType` transfer property is not provided.
     * - Sets `CartChange.items.shipment.shipmentTypeUuid` taken from `CartChange.items.shipmentType.uuid`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeItemsWithShipmentType(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Requires `QuoteTransfer.items.shipment` transfer property to be set.
     * - Expects `QuoteTransfer.items.shipmentType.uuid` transfer property to be set.
     * - Does nothing if `QuoteTransfer.items.shipmentType` is not provided.
     * - Sets `QuoteTransfer.items.shipment.shipmentTypeUuid` taken from `QuoteTransfer.items.shipmentType.uuid`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItemsWithShipmentType(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Requires `QuoteTransfer.store.name` transfer property to be set.
     * - Expects `QuoteTransfer.items.shipment.shipmentTypeUuid` transfer property to be provided.
     * - Expects `QuoteTransfer.items.shipment.method.shipmentType.uuid` transfer property to be provided.
     * - Expects `QuoteTransfer.items.shipment.method.shipmentType.name` transfer property to be provided.
     * - Checks if selected shipment type matches selected shipment method's shipment type.
     * - Checks if selected shipment type is active and available for store provided in `QuoteTransfer.store`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteReadyForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;
}
