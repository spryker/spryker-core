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
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerFacadeInterface;

class ShipmentGrouper implements ShipmentGrouperInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface
     */
    protected $shipmentMethodReader;

    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface $shipmentMethodReader
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentToCustomerFacadeInterface $customerFacade,
        ShipmentMapperInterface $shipmentMapper,
        MethodReaderInterface $shipmentMethodReader,
        ShipmentServiceInterface $shipmentService
    ) {
        $this->customerFacade = $customerFacade;
        $this->shipmentMapper = $shipmentMapper;
        $this->shipmentMethodReader = $shipmentMethodReader;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     * @param bool[] $itemListUpdatedStatus
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransfer(
        ShipmentFormTransfer $shipmentFormTransfer,
        array $itemListUpdatedStatus
    ): ShipmentGroupTransfer {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer = $this->addShipmentTransfer($shipmentGroupTransfer, $shipmentFormTransfer);
        $shipmentGroupTransfer = $this->addShipmentItems(
            $shipmentGroupTransfer,
            $shipmentFormTransfer,
            $itemListUpdatedStatus
        );
        $shipmentGroupTransfer = $this->addShipmentHashKey($shipmentGroupTransfer);

        return $shipmentGroupTransfer;
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

        if ($shipmentFormTransfer->getIdCustomerAddress()) {
            $shippingAddress = $this->customerFacade
                ->findCustomerAddressById($shipmentFormTransfer->getIdCustomerAddress());
            $shipmentTransfer->setShippingAddress($shippingAddress);
        }

        $shipmentGroupTransfer->setShipment($shipmentTransfer);

        if ($shipmentFormTransfer->getIdShipmentMethod() === null) {
            return $shipmentGroupTransfer;
        }

        $shipmentMethodTransfer = $this->shipmentMethodReader
            ->findShipmentMethodTransferById($shipmentFormTransfer->getIdShipmentMethod());
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

        return $idSalesOrderItem !== null
            && isset($itemListUpdatedStatus[$idSalesOrderItem])
            && is_bool($itemListUpdatedStatus[$idSalesOrderItem])
            && $itemListUpdatedStatus[$idSalesOrderItem];
    }
}
