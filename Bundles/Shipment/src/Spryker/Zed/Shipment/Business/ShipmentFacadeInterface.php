<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;

interface ShipmentFacadeInterface
{
    /**
     * Specification:
     * - Creates carrier using provided ShipmentCarrier transfer object data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     */
    public function createCarrier(ShipmentCarrierTransfer $carrierTransfer);

    /**
     * Specification:
     * - Retrieves the list of carriers from database as transfer object collection.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer[]
     */
    public function getCarriers();

    /**
     * Specification:
     * - Retrieves the list of shipment methods from database as transfer object collection.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getMethods();

    /**
     * Specification:
     * - Creates shipment method in database using provided ShipmentMethod transfer object data.
     * - Creates shipment method prices in database using "prices" collection defined in ShipmentMethod transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function createMethod(ShipmentMethodTransfer $methodTransfer);

    /**
     * Specification:
     * - Retrieves a shipment method from database by ID.
     * - Returns NULL if the method does not exist.
     *
     * @api
     *
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findMethodById($idShipmentMethod);

    /**
     * Specification:
     * - Retrieves active shipment methods.
     * - Calculates shipment method delivery time using its assigned ShipmentMethodDeliveryTimePluginInterface plugin.
     * - Selects shipment method price for the provided currency and current store.
     * - Overrides shipment method price using its assigned ShipmentMethodPricePluginInterface plugin if there is any.
     * - Excludes shipment methods which do not have a valid price as a result.
     * - Excludes shipment methods which do not fulfill their assigned ShipmentMethodAvailabilityPluginInterface plugin requirements.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Retrieves active shipment method buy id shipment method.
     * - Calculates shipment method delivery time using its assigned ShipmentMethodDeliveryTimePluginInterface plugin.
     * - Selects shipment method price for the provided currency and current store.
     * - Overrides shipment method price using its assigned ShipmentMethodPricePluginInterface plugin if there is any.
     * - Excludes shipment methods which do not have a valid price as a result.
     * - Excludes shipment methods which do not fulfill their assigned ShipmentMethodAvailabilityPluginInterface plugin requirements.
     *
     * @api
     *
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findAvailableMethodById($idShipmentMethod, QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Retrieves a shipment method from database by ID.
     *
     * @api
     *
     * @param int $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod);

    /**
     * Specification:
     * - Checks if the shipment method exists in database.
     *
     * @api
     *
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod);

    /**
     * Specification:
     * - Deletes shipment method from database using provided ID.
     *
     * @api
     *
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod);

    /**
     * Specification:
     * - Updates shipment method in database using provided ShipmentMethod transfer object data.
     * - Updates/creates shipment method prices using "prices" collection in ShipmentMethod transfer object.
     * - Returns with shipment method's primary key on success.
     * - Returns false if shipment method was not found in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int|bool
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer);

    /**
     * Specification:
     * - Updates tax rates in Quote transfer object if shipment method is set in Quote transfer object.
     * - Selects shipment method tax rate using shipping address's country code.
     * - Uses default tax rate if shipping address is not defined in Quote transfer object.
     * - Sets tax rate in provided Quote transfer object's shipment method.
     * - Sets tax rate in provided Quote transfer object's selected shipment expense.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateShipmentTaxRate(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Adds shipment sales expense to sales order.
     * - Creates sales shipment for sales order.
     *
     * @api
     *
     * @deprecated Use saveOrderShipment() instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Adds shipment sales expense to sales order.
     * - Creates sales shipment for sales order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderShipment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

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

    /**
     * Specification:
     * - Transforms provided ShipmentMethod entity into ShipmentMethod transfer object.
     * - ShipmentMethod transfer object's CarrierName field is populated using ShipmentMethod entity's carrier connection.
     * - ShipmentMethod entity related ShipmentMethodPrice entities are transformed to MoneyValue transfer object collection.
     * - Currency transfer object in MoneyValue transfer objects is populated using the corresponding ShipmentMethodPrice entity's currency reference.
     *
     * @api
     *
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function transformShipmentMethodEntityToShipmentMethodTransfer(SpyShipmentMethod $shipmentMethodEntity);

    /**
     * Specification:
     * - Returns shipment expense type, used to identify expense used for shipment.
     *
     * @api
     *
     * @return string
     */
    public function getShipmentExpenseTypeIdentifier();

    /**
     * Specification:
     *  - Check if provided shipment is activated, have is_active set to true.
     *
     * @api
     *
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function isShipmentMethodActive($idShipmentMethod);

    /**
     * Specification:
     * - Filters obsolete shipment expenses from Quote if shipment method is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function filterObsoleteShipmentExpenses(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * Specification
     * - Checks if shipment method name is unique for carrier.
     * - If $shipmentMethodTransfer::idShipmentMethod provided, it will be excluded from the check.
     * - Requires name field to be set in ShipmentMethodTransfer
     * - Requires FkShipmentCarrier field to be set in ShipmentMethodTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function isShipmentMethodUniqueForCarrier(ShipmentMethodTransfer $shipmentMethodTransfer): bool;
}
