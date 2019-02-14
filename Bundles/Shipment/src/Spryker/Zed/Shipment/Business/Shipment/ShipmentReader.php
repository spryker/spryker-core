<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Shipment;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentReader implements ShipmentReaderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        ShipmentQueryContainerInterface $queryContainer,
        ShipmentToSalesFacadeInterface $salesFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idSalesShipment): ?ShipmentTransfer
    {
        $shipmentEntity = $this->queryContainer
            ->querySalesShipmentById($idSalesShipment)
            ->find()
            ->getFirst();

        if ($shipmentEntity === null) {
            return null;
        }

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer = $this->mapShipmentEntityToShipmentTransfer($shipmentEntity, $shipmentTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $shipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function mapShipmentEntityToShipmentTransfer(SpySalesShipment $shipmentEntity, ShipmentTransfer $shipmentTransfer): ShipmentTransfer
    {
        $shipmentTransfer->fromArray($shipmentEntity->toArray(), true);

        $shipmentTransfer->setShippingAddress(
            $this->salesFacade->findOrderAddressByIdOrderAddress($shipmentEntity->getFkSalesOrderAddress())
        );

        $shipmentTransfer->setMethod(
            $this->getShipmentMethodTransferByName($shipmentEntity->getName())
        );

        return $shipmentTransfer;
    }

    /**
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function getShipmentMethodTransferByName(string $shipmentMethodName): ShipmentMethodTransfer
    {
        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodEntity = $this->queryContainer
            ->queryMethodsWithMethodPricesAndCarrier()
            ->filterByName($shipmentMethodName)
            ->find()
            ->getFirst();

        return $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
    }
}
