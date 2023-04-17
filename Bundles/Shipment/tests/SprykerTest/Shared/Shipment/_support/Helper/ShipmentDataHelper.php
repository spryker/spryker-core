<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Shipment\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShipmentDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param int $idSalesOrder
     * @param array $overrideShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function haveShipment(int $idSalesOrder, array $overrideShipment = []): ShipmentTransfer
    {
        $shipmentTransfer = (new ShipmentBuilder($overrideShipment))->build();
        $salesShipmentEntity = $this->saveShipment($shipmentTransfer, $idSalesOrder);
        $shipmentTransfer->fromArray($salesShipmentEntity->toArray(), true);

        $this->debug(sprintf(
            'Inserted Sales shipment: %d for sales order: %d',
            $shipmentTransfer->getIdSalesShipment(),
            $idSalesOrder,
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($shipmentTransfer): void {
            $this->cleanupSalesShipment($shipmentTransfer->getIdSalesShipment());
        });

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    protected function saveShipment(ShipmentTransfer $shipmentTransfer, int $idSalesOrder): SpySalesShipment
    {
        $shipmentEntity = new SpySalesShipment();
        $shipmentEntity->fromArray($shipmentTransfer->toArray());
        $shipmentEntity->setFkSalesOrder($idSalesOrder);

        if ($shipmentTransfer->getShippingAddress()) {
            $shipmentEntity->setFkSalesOrderAddress($shipmentTransfer->getShippingAddress()->getIdSalesOrderAddress());
        }

        $shipmentEntity->save();

        return $shipmentEntity;
    }

    /**
     * @return \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected function getShipmentQuery(): ShipmentQueryContainerInterface
    {
        return $this->getLocator()->shipment()->queryContainer();
    }

    /**
     * @param int $idSalesShipment
     *
     * @return void
     */
    protected function cleanupSalesShipment(int $idSalesShipment): void
    {
        $this->debug(sprintf('Deleting Sales shipment: %d', $idSalesShipment));

        SpySalesShipmentQuery::create()->filterByIdSalesShipment($idSalesShipment)->delete();
    }
}
