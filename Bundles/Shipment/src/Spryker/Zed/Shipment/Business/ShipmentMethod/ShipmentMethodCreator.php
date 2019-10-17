<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\Model\MethodPriceInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentMethodCreator implements ShipmentMethodCreatorInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $shipmentEntityManager;

    /**
     * @var \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface
     */
    protected $methodPrice;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationUpdaterInterface
     */
    protected $storeRelationUpdater;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $shipmentEntityManager
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationUpdaterInterface $storeRelationUpdater
     */
    public function __construct(
        ShipmentEntityManagerInterface $shipmentEntityManager,
        MethodPriceInterface $methodPrice,
        ShipmentMethodStoreRelationUpdaterInterface $storeRelationUpdater
    ) {
        $this->shipmentEntityManager = $shipmentEntityManager;
        $this->methodPrice = $methodPrice;
        $this->storeRelationUpdater = $storeRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return int|null
     */
    public function createShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): ?int
    {
        $shipmentMethodTransfer = $this->shipmentEntityManager->saveSalesShipmentMethod($shipmentMethodTransfer);
        $shipmentMethodTransfer->requireStoreRelation()
            ->getStoreRelation()
                ->setIdEntity($shipmentMethodTransfer->getIdShipmentMethod());
        $this->methodPrice->save($shipmentMethodTransfer);
        $this->storeRelationUpdater->update($shipmentMethodTransfer->getStoreRelation());

        return $shipmentMethodTransfer->getIdShipmentMethod();
    }
}
