<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;

interface ShipmentMapperInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapShipmentTransferToShipmentEntity(
        SpySalesShipment $salesShipmentEntity,
        ShipmentTransfer $shipmentTransfer
    ): SpySalesShipment;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapOrderTransferToShipmentEntity(
        SpySalesShipment $salesShipmentEntity,
        OrderTransfer $orderTransfer
    ): SpySalesShipment;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ExpenseTransfer|null $expenseTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapExpenseTransferToShipmentEntity(
        SpySalesShipment $salesShipmentEntity,
        ?ExpenseTransfer $expenseTransfer = null
    ): SpySalesShipment;

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapShipmentEntityToShipmentTransfer(
        ShipmentTransfer $shipmentTransfer,
        SpySalesShipment $salesShipmentEntity
    ): ShipmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentEntityToShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        SpySalesShipment $salesShipment
    ): ShipmentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function mapShipmentEntityToShipmentCarrierTransfer(
        ShipmentCarrierTransfer $shipmentCarrierTransfer,
        SpySalesShipment $salesShipment
    ): ShipmentCarrierTransfer;

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipment
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapShipmentEntityToShippingAddressTransfer(
        AddressTransfer $addressTransfer,
        SpySalesShipment $salesShipment
    ): AddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapShipmentEntityToShipmentTransferWithDetails(
        ShipmentTransfer $shipmentTransfer,
        SpySalesShipment $salesShipmentEntity
    ): ShipmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $salesShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentMethodEntityToShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        SpyShipmentMethod $salesShipmentMethod
    ): ShipmentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentTransferToShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentMethodTransfer;
}
