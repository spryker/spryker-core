<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Shipment;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentReader implements ShipmentReaderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     */
    public function __construct(
        ShipmentToSalesFacadeInterface $salesFacade,
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentMapperInterface $shipmentMapper
    ) {
        $this->salesFacade = $salesFacade;
        $this->shipmentRepository = $shipmentRepository;
        $this->shipmentMapper = $shipmentMapper;
    }

    /**
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idSalesShipment): ?ShipmentTransfer
    {
        $shipmentEntity = $this->shipmentRepository
            ->querySalesShipmentById($idSalesShipment)
            ->find()
            ->getFirst();

        if ($shipmentEntity === null) {
            return null;
        }

        $shipmentTransfer = $this->shipmentMapper
            ->mapShipmentEntityToShipmentTransfer($shipmentEntity, new ShipmentTransfer());

        $shipmentAddressTransfer = $this->salesFacade
            ->findOrderAddressByIdOrderAddress($shipmentEntity->getFkSalesOrderAddress());
        $shipmentTransfer->setShippingAddress($shipmentAddressTransfer);

        $shipmentMethodTransfer = $this->getShipmentMethodTransferByName($shipmentEntity->getName());
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function getShipmentMethodTransferByName(string $shipmentMethodName): ShipmentMethodTransfer
    {
        $shipmentMethodEntity = $this->shipmentRepository
            ->queryMethodsWithMethodPricesAndCarrier()
            ->filterByName($shipmentMethodName)
            ->find()
            ->getFirst();

        return $this->shipmentMapper
            ->mapShipmentEntityToShipmentMethodTransfer($shipmentMethodEntity, new ShipmentMethodTransfer());
    }
}
