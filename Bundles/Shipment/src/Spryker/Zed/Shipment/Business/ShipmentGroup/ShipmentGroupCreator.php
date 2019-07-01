<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentGroupCreator implements ShipmentGroupCreatorInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentServiceInterface $shipmentService,
        ShipmentToSalesFacadeInterface $salesFacade
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->shipmentService = $shipmentService;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param bool[] $itemListUpdatedStatus
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransferWithListedItems(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $itemListUpdatedStatus
    ): ShipmentGroupTransfer {
        $shipmentGroupTransfer = $this->expandShipmentTransfer($shipmentGroupTransfer);
        $shipmentGroupTransfer = $this->addShipmentItems($shipmentGroupTransfer, $itemListUpdatedStatus);

        return $this->addShipmentHashKey($shipmentGroupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function expandShipmentTransfer(
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ShipmentGroupTransfer {
        $shipmentTransfer = $shipmentGroupTransfer->requireShipment()->getShipment();

        $shipmentAddressTransfer = $this->salesFacade
            ->expandWithCustomerOrSalesAddress($shipmentTransfer->getShippingAddress());

        $shipmentTransfer->setShippingAddress($shipmentAddressTransfer);
        $shipmentGroupTransfer->setShipment($shipmentTransfer);

        $shipmentMethodTransfer = $shipmentTransfer->getMethod();
        if ($shipmentMethodTransfer === null || $shipmentMethodTransfer->getIdShipmentMethod() === null) {
            return $shipmentGroupTransfer;
        }

        $shipmentMethodTransfer = $this->shipmentRepository
            ->findShipmentMethodByIdWithPricesAndCarrier($shipmentMethodTransfer->getIdShipmentMethod());
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param bool[] $itemListUpdatedStatus
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function addShipmentItems(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $itemListUpdatedStatus
    ): ShipmentGroupTransfer {
        $shipmentTransfer = $shipmentGroupTransfer->requireShipment()->getShipment();
        $idSalesShipmentOfShipmentGroup = $shipmentTransfer->getIdSalesShipment();
        $items = new ArrayObject();
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $idItemShipment = $itemTransfer->requireShipment()->getShipment()->getIdSalesShipment();
            if ($idItemShipment !== $idSalesShipmentOfShipmentGroup && !$this->isItemSelected($itemTransfer, $itemListUpdatedStatus)) {
                continue;
            }

            $clonedItemTransfer = clone $itemTransfer;
            $clonedItemTransfer->setShipment($shipmentTransfer);
            $items->append($clonedItemTransfer);
        }

        return $shipmentGroupTransfer->setItems($items);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function addShipmentHashKey(ShipmentGroupTransfer $shipmentGroupTransfer): ShipmentGroupTransfer
    {
        $shipmentTransfer = $shipmentGroupTransfer->requireShipment()->getShipment();
        $shipmentHashKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);

        return $shipmentGroupTransfer->setHash($shipmentHashKey);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param bool[] $itemListUpdatedStatus
     *
     * @return bool
     */
    protected function isItemSelected(ItemTransfer $itemTransfer, array $itemListUpdatedStatus): bool
    {
        $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();

        return isset($itemListUpdatedStatus[$idSalesOrderItem])
            && is_scalar($itemListUpdatedStatus[$idSalesOrderItem])
            && $itemListUpdatedStatus[$idSalesOrderItem];
    }
}
