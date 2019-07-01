<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentFormTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentGroupCreator implements ShipmentGroupCreatorInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

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
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        ShipmentMapperInterface $shipmentMapper,
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentServiceInterface $shipmentService,
        ShipmentToSalesFacadeInterface $salesFacade
    ) {
        $this->shipmentMapper = $shipmentMapper;
        $this->shipmentRepository = $shipmentRepository;
        $this->shipmentService = $shipmentService;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     * @param bool[] $itemListUpdatedStatus
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransferWithListedItems(
        ShipmentFormTransfer $shipmentFormTransfer,
        array $itemListUpdatedStatus
    ): ShipmentGroupTransfer {
        $shipmentGroupTransfer = $this->addShipmentTransfer(new ShipmentGroupTransfer(), $shipmentFormTransfer);
        $shipmentGroupTransfer = $this->addShipmentItems(
            $shipmentGroupTransfer,
            $shipmentFormTransfer,
            $itemListUpdatedStatus
        );

        return $this->addShipmentHashKey($shipmentGroupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function addShipmentTransfer(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        ShipmentFormTransfer $shipmentFormTransfer
    ): ShipmentGroupTransfer {
        $shipmentTransfer = $this->shipmentMapper
            ->mapFormDataToShipmentTransfer($shipmentFormTransfer, new ShipmentTransfer());

        $shipmentAddressTransfer = $this->salesFacade
            ->expandWithCustomerOrSalesAddress($shipmentTransfer->getShippingAddress());
        $shipmentTransfer->setShippingAddress($shipmentAddressTransfer);
        $shipmentGroupTransfer->setShipment($shipmentTransfer);

        if ($shipmentFormTransfer->getIdShipmentMethod() === null) {
            return $shipmentGroupTransfer;
        }

        $shipmentMethodTransfer = $this->shipmentRepository
            ->findShipmentMethodByIdWithPricesAndCarrier($shipmentFormTransfer->getIdShipmentMethod());
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     * @param bool[] $itemListUpdatedStatus
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function addShipmentItems(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        ShipmentFormTransfer $shipmentFormTransfer,
        array $itemListUpdatedStatus
    ): ShipmentGroupTransfer {
        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        foreach ($shipmentFormTransfer->getItems() as $itemTransfer) {
            if (!$this->isItemForUpdate($itemTransfer, $itemListUpdatedStatus)) {
                continue;
            }

            $itemTransfer->setShipment($shipmentTransfer);
            $shipmentGroupTransfer->addItem($itemTransfer);
        }

        return $shipmentGroupTransfer;
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
    protected function isItemForUpdate(ItemTransfer $itemTransfer, array $itemListUpdatedStatus): bool
    {
        $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();

        return isset($itemListUpdatedStatus[$idSalesOrderItem])
            && is_scalar($itemListUpdatedStatus[$idSalesOrderItem])
            && $itemListUpdatedStatus[$idSalesOrderItem];
    }
}
