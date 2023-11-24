<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesShipmentCollectionTransfer;
use Generated\Shared\Transfer\SalesShipmentCriteriaTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentCarrierRequestTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentGroupResponseTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentMethodPluginCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
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
     * @return array<\Generated\Shared\Transfer\ShipmentCarrierTransfer>
     */
    public function getCarriers();

    /**
     * Specification:
     * - Retrieves the carrier by ShipmentCarrierRequestTransfer from database as transfer object.
     * - Filters by idCarrier when provided.
     * - Filters by carrierName when provided.
     * - Excludes carriers which id exists in excludedCarrierIds list.
     * - Returns NULL if the carrier does not exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentCarrierRequestTransfer $shipmentCarrierRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer|null
     */
    public function findShipmentCarrier(ShipmentCarrierRequestTransfer $shipmentCarrierRequestTransfer): ?ShipmentCarrierTransfer;

    /**
     * Specification:
     * - Retrieves the list of available shipment methods from database as transfer object collection
     * grouped by shipment hash.
     * - Executes a stack of {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ShipmentMethodTransfer>
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
     * - Executes a stack of {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface} plugins.
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
     * - Retrieves active shipment methods for every shipment group in QuoteTransfer.
     * - Filters by idStore from Quote.
     * - Calculates shipment method delivery time using its assigned ShipmentMethodDeliveryTimePluginInterface plugin.
     * - Selects shipment method price for the provided currency and store from quote level.
     * - Overrides shipment method price using its assigned ShipmentMethodPricePluginInterface plugin if there is any.
     * - Excludes shipment methods which do not have a valid price or ShipmentMethodPricePluginInterface as a result.
     * - Excludes shipment methods which do not fulfill their assigned ShipmentMethodAvailabilityPluginInterface plugin.
     * requirements.
     * - Executes a stack of {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentMethodsCollectionTransfer;

    /**
     * Specification:
     * - Retrieves active shipment method by id shipment method and id store.
     * - Calculates shipment method delivery time using its assigned ShipmentMethodDeliveryTimePluginInterface plugin.
     * - Selects shipment method price for the provided currency and store from quote level.
     * - Overrides shipment method price using its assigned ShipmentMethodPricePluginInterface plugin if there is any.
     * - Excludes shipment methods which do not have a valid price or ShipmentMethodPricePluginInterface as a result.
     * - Excludes shipment methods which do not fulfill their assigned ShipmentMethodAvailabilityPluginInterface plugins.
     * requirements.
     * - Executes a stack of {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface} plugins.
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
     * - Selects shipment method tax rates using shipping address's country code of all shipments (quote or item level).
     * - Uses default tax rate if shipping address is not defined on quote level (BC) or item level.
     * - Sets tax rate in provided Quote transfer object's quote level (BC) or item level shipment methods.
     * - Sets tax rate in provided Quote transfer object's quote level (BC) or item level shipment expenses.
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
     * - Selects shipment method tax rates using shipping address's country code.
     * - Uses default tax rate if shipping address is not defined.
     * - Sets tax rate to provided object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function calculateShipmentTaxRateByCalculableObject(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer;

    /**
     * Specification:
     * - Adds shipment sales expenses to sales order according to quote level (BC) or item level shipments.
     * - Expands shipment expense with a stack of ShipmentExpenseExpanderPluginInterface before shipment saving
     * in case of multi shipment.
     * - Creates sales shipments for sales order.
     * - Creates sales shipping addresses for each item level shipment.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface::saveSalesOrderShipment()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderShipment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     * Specification:
     * - Expands shipment expense with a stack of ShipmentExpenseExpanderPluginInterface
     * - Creates sales shipments for sales order.
     * - Creates sales shipping addresses for each item level shipment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrderShipment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     *   - Hydrates order transfer with additional shipment data from shipment sales tables.
     *   - Sorts order items by shipment ids in the case when multiple shipment addresses are defined in order items.
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
     * - ShipmentMethod transfer object's CarrierName field is populated using ShipmentMethod entity's carrier
     * connection.
     * - ShipmentMethod entity related ShipmentMethodPrice entities are transformed to MoneyValue transfer object
     * collection.
     * - Currency transfer object in MoneyValue transfer objects is populated using the corresponding
     * ShipmentMethodPrice entity's currency reference.
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
     * @deprecated Use {@link \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE} instead.
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
     * - Returns ShipmentTransfer for provided id or null.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface::getSalesShipmentCollection()} instead.
     *
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idSalesShipment): ?ShipmentTransfer;

    /**
     * Specification:
     * - Creates new or update existing shipment for specified order in Zed.
     * - Uses shipment saving logic from the saveOrderShipment() method.
     * - Adds shipment sales expenses to sales order according to quote level (BC) or item level shipments.
     * - Expands shipment expense with a stack of ShipmentExpenseExpanderPluginInterface before shipment saving.
     * - Creates or updates sales shipment.
     * - Creates or updates sales shipping addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupResponseTransfer
     */
    public function saveShipment(ShipmentGroupTransfer $shipmentGroupTransfer, OrderTransfer $orderTransfer): ShipmentGroupResponseTransfer;

    /**
     * Specification:
     * - Creates new ShipmentGroupTransfer for specified order in Zed.
     * - Uses shipment findShipmentMethodTransferById logic from the ShipmentReader class.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param array<bool> $itemListUpdatedStatus
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransferWithListedItems(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $itemListUpdatedStatus
    ): ShipmentGroupTransfer;

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
     * Specification:
     * - Checks if shipment method name is unique for carrier.
     * - If $shipmentMethodTransfer::idShipmentMethod provided, it will be excluded from the check.
     * - Requires ShipmentMethodTransfer::name and ShipmentMethodTransfer::fkShipmentCarrier fields to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function isShipmentMethodUniqueForCarrier(ShipmentMethodTransfer $shipmentMethodTransfer): bool;

    /**
     * Specification:
     * - Expands quote items with shipments.
     * - Expands quote expenses with shipment expenses.
     * - If `QuoteTransfer.skipRecalculation` is set to true, skips quote recalculation.
     * - Otherwise, executes {@link \Spryker\Zed\Calculation\Business\CalculationFacadeInterface::recalculateQuote()} method.
     * - Uses {@link \Spryker\Zed\Shipment\ShipmentConfig::shouldExecuteQuotePostRecalculationPlugins()} method to determine if quote post recalculate plugins should be executed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Returns sales order items by sales order id and salesShipmentId or null.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesShipment
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function findSalesOrderItemsIdsBySalesShipmentId(int $idSalesOrder, int $idSalesShipment): ArrayObject;

    /**
     * Specification:
     * - Expands order mail transfer data with shipment groups data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function expandOrderMailTransfer(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer;

    /**
     * Specification:
     * - Expands order items with shipment.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithShipment(array $itemTransfers): array;

    /**
     * Specification:
     * - Groups manual events by sales shipment id.
     *
     * @api
     *
     * @param array $events
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $orderItemTransfers
     *
     * @return array
     */
    public function groupEventsByShipment(array $events, iterable $orderItemTransfers): array;

    /**
     * Specification:
     * - Finds shipment method by the given name.
     * - Executes a stack of {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByName(string $shipmentMethodName): ?ShipmentMethodTransfer;

    /**
     * Specification:
     * - Finds shipment method by the given key.
     * - Executes a stack of {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param string $shipmentMethodKey
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByKey(string $shipmentMethodKey): ?ShipmentMethodTransfer;

    /**
     * Specification:
     * - Returns the shipment method plugins grouped by the type.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodPluginCollectionTransfer
     */
    public function getShipmentMethodPlugins(): ShipmentMethodPluginCollectionTransfer;

    /**
     * Specification:
     * - Returns active shipment carriers.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ShipmentCarrierTransfer>
     */
    public function getActiveShipmentCarriers(): array;

    /**
     * Specification:
     * - Calculates shipment total using expenses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateShipmentTotal(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * Specification:
     * - Retrieves shipment entities filtered by the criteria.
     * - Uses `SalesShipmentCriteriaTransfer.salesShipmentConditions.salesShipmentIds` to filter shipments by `idSalesShipment`.
     * - Uses `SalesShipmentCriteriaTransfer.salesShipmentConditions.orderItemUuids` to filter shipments by order item uuids.
     * - Uses `SalesShipmentCriteriaTransfer.salesShipmentConditions.salesOrderItemIds` to filter shipments by `idSalesOrderItem`.
     * - Uses `SalesShipmentCriteriaTransfer.sort.field` to set the `order by` field.
     * - Uses `SalesShipmentCriteriaTransfer.sort.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `SalesShipmentCriteriaTransfer.pagination.{limit, offset}` to paginate result with `limit` and `offset`.
     * - Uses `SalesShipmentCriteriaTransfer.pagination.{page, maxPerPage}` to paginate result with `page` and `maxPerPage`.
     * - Uses `SalesShipmentCriteriaTransfer.salesShipmentConditions.withOrderItems` to expand shipments with order items.
     * - Returns `SalesShipmentCollectionTransfer` filled with found sales shipments.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentCollectionTransfer
     */
    public function getSalesShipmentCollection(SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer): SalesShipmentCollectionTransfer;

    /**
     * Specification:
     * - Retrieves shipment methods filtered by the criteria.
     * - Uses `ShipmentMethodCriteriaTransfer.shipmentMethodConditions.shipmentMethodIds` to filter shipment methods by shipment method IDs.
     * - Uses `ShipmentMethodCriteriaTransfer.shipmentMethodConditions.shipmentCarrierIds` to filter shipment methods by shipment carrier IDs.
     * - Uses `ShipmentMethodCriteriaTransfer.shipmentMethodConditions.storeNames` to filter shipment methods by related store names.
     * - Uses `ShipmentMethodCriteriaTransfer.shipmentMethodConditions.isActive` to filter shipment methods by `isActive` status.
     * - Uses `ShipmentMethodCriteriaTransfer.shipmentMethodConditions.isActiveShipmentCarrier` to filter shipment methods by shipment carrier's `isActive` status.
     * - Uses `ShipmentMethodCriteriaTransfer.sort.field` to set the `order by` field.
     * - Uses `ShipmentMethodCriteriaTransfer.sort.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `ShipmentMethodCriteriaTransfer.pagination.{limit, offset}` to paginate result with `limit` and `offset`.
     * - Uses `ShipmentMethodCriteriaTransfer.pagination.{page, maxPerPage}` to paginate result with `page` and `maxPerPage`.
     * - Executes a stack of {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface} plugins.
     * - Returns `ShipmentMethodCollectionTransfer` filled with found shipment methods.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollection(ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer): ShipmentMethodCollectionTransfer;
}
