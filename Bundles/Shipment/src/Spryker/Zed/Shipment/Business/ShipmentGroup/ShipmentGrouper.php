<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ShipmentFormTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
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
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface $shipmentMethodReader
     */
    public function __construct(
        ShipmentToCustomerFacadeInterface $customerFacade,
        ShipmentMapperInterface $shipmentMapper,
        MethodReaderInterface $shipmentMethodReader
    ) {
        $this->customerFacade = $customerFacade;
        $this->shipmentMapper = $shipmentMapper;
        $this->shipmentMethodReader = $shipmentMethodReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransfer(ShipmentFormTransfer $shipmentFormTransfer): ShipmentGroupTransfer
    {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer = $this->addShipmentTransfer($shipmentGroupTransfer, $shipmentFormTransfer);
        $shipmentGroupTransfer = $this->addShipmentItems($shipmentGroupTransfer, $shipmentFormTransfer);

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
        $shipmentTransfer = $this->shipmentMapper->mapFormDataToShipmentTransfer($shipmentFormTransfer, new ShipmentTransfer());

        if ($shipmentFormTransfer->getIdCustomerAddress()) {
            $shippingAddress = $this->customerFacade->findCustomerAddressById($shipmentFormTransfer->getIdCustomerAddress());
            $shipmentTransfer->setShippingAddress($shippingAddress);
        }

        $shipmentGroupTransfer->setShipment($shipmentTransfer);

        if ($shipmentFormTransfer->getIdShipmentMethod() === null) {
            return $shipmentGroupTransfer;
        }

        $shipmentMethodTransfer = $this->shipmentMethodReader->findShipmentMethodById($shipmentFormTransfer->getIdShipmentMethod());
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function addShipmentItems(ShipmentGroupTransfer $shipmentGroupTransfer, ShipmentFormTransfer $shipmentFormTransfer): ShipmentGroupTransfer
    {
        foreach ($shipmentFormTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIsUpdated() === true) {
                $shipmentGroupTransfer->addItem($itemTransfer);
            }
        }

        return $shipmentGroupTransfer;
    }
}
