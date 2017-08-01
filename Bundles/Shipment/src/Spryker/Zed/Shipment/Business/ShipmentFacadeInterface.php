<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface ShipmentFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     */
    public function createCarrier(ShipmentCarrierTransfer $carrierTransfer);

    /**
     * Specification:
     * - Finds list of carrier transfers from database
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer[]
     */
    public function getCarriers();

    /**
     * Specification:
     * - Finds list of shipment method transfers from database
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getMethods();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function createMethod(ShipmentMethodTransfer $methodTransfer);

    /**
     * Specification:
     * - Finds a shipment method by ID
     * - Returns NULL if the method does not exist
     *
     * @api
     *
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findMethodById($idShipmentMethod);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param int $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod);

    /**
     * @api
     *
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod);

    /**
     * @api
     *
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateShipmentTaxRate(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     *   - Hydrates order transfer with additional shipment data from shipment sales tables.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderShipment(OrderTransfer $orderTransfer);

}
