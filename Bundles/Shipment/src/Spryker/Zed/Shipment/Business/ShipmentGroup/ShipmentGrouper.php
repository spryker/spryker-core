<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface;

class ShipmentGrouper implements ShipmentGrouperInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface
     */
    protected $methodReader;

    /**
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface $methodReader
     */
    public function __construct(ShipmentMapperInterface $shipmentMapper, MethodReaderInterface $methodReader)
    {
        $this->shipmentMapper = $shipmentMapper;
        $this->methodReader = $methodReader;
    }

    /**
     * @param array $formData
     * @param int|null $idCustomerAddress
     * @param int|null $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransfer(
        array $formData,
        ?int $idCustomerAddress,
        ?int $idShipmentMethod
    ): ShipmentGroupTransfer {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer = $this->addShipmentTransfer(
            $shipmentGroupTransfer,
            $formData,
            $idCustomerAddress,
            $idShipmentMethod
        );

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param array $formData
     * @param int|null $idCustomerAddress
     * @param int|null $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function addShipmentTransfer(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $formData,
        ?int $idCustomerAddress = null,
        ?int $idShipmentMethod = null
    ): ShipmentGroupTransfer {
        $shipmentTransfer = $this->shipmentMapper->mapFormDataToShipmentTransfer($formData, new ShipmentTransfer());

        if ($idCustomerAddress !== null) {
            $shipmentTransfer->requireShippingAddress()
                ->getShippingAddress()
                ->setIdCustomerAddress($idCustomerAddress);
        }

        $shipmentGroupTransfer->setShipment($shipmentTransfer);

        if ($idShipmentMethod === null) {
            return $shipmentGroupTransfer;
        }

        $shipmentMethodTransfer = $this->methodReader->findShipmentMethodTransferById($idShipmentMethod);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        return $shipmentGroupTransfer;
    }
}
