<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentTransformerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class Shipment implements ShipmentInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentTransformerInterface
     */
    protected $shipmentTransformer;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentTransformerInterface $shipmentTransformer
     */
    public function __construct(
        ShipmentQueryContainerInterface $queryContainer,
        ShipmentTransformerInterface $shipmentTransformer
    ) {
        $this->queryContainer = $queryContainer;
        $this->shipmentTransformer = $shipmentTransformer;
    }

    /**
     * @param int $idShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getShipmentTransferById(int $idShipment): ShipmentTransfer
    {
        $shipmentTransfer = new ShipmentTransfer();

        $shipmentQuery = $this->queryContainer->querySalesShipmentById($idShipment);
        $shipmentTransferEntity = $shipmentQuery->findOne();

        $shipmentTransfer = $this->mapEntityToTransfer($shipmentTransferEntity, $shipmentTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $shipmentEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function transformShipment(SpySalesShipment $shipmentEntity)
    {
        $shipmentTransfer = $this->shipmentTransformer->transformEntityToTransfer($shipmentEntity);

        return $shipmentTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $shipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function mapEntityToTransfer(SpySalesShipment $shipmentEntity, ShipmentTransfer $shipmentTransfer): ShipmentTransfer
    {
        $shipmentTransfer->fromArray(
            $shipmentEntity->toArray(TableMap::TYPE_PHPNAME, true, [], true),
            true
        );

        $shipmentTransfer->setShippingAddress(
            (new AddressTransfer())->fromArray(
                $shipmentEntity->getSpySalesOrderAddress()->toArray(TableMap::TYPE_PHPNAME),
                true
            )
        );

        return $shipmentTransfer;
    }

    /**
     * @param int $idSalesShipment
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function findShipmentItemsByIdSalesShipment(int $idSalesShipment): ObjectCollection
    {
        $query = $this->queryContainer->querySalesOrderItemsByIdSalesShipment($idSalesShipment);
        $shipmentItems = $query->find();

        return $shipmentItems;
    }
}
