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
        $this->saveShipment($shipmentTransfer, $idSalesOrder);

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int $idSalesOrder
     *
     * @return int
     */
    protected function saveShipment(ShipmentTransfer $shipmentTransfer, int $idSalesOrder): int
    {
        $shipmentEntity = new SpySalesShipment();
        $shipmentEntity->fromArray($shipmentTransfer->toArray());
        $shipmentEntity->fromArray($shipmentTransfer->getMethod()->toArray());
        $shipmentEntity->setFkSalesOrder($idSalesOrder);
        $shipmentEntity->save();

        return $shipmentEntity->getIdSalesShipment();
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
