<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Shipment\Business\Model\MethodPriceInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentMethodUpdater implements ShipmentMethodUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

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
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $shipmentEntityManager
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationUpdaterInterface $storeRelationUpdater
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentEntityManagerInterface $shipmentEntityManager,
        MethodPriceInterface $methodPrice,
        ShipmentMethodStoreRelationUpdaterInterface $storeRelationUpdater
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->shipmentEntityManager = $shipmentEntityManager;
        $this->methodPrice = $methodPrice;
        $this->storeRelationUpdater = $storeRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function updateShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shipmentMethodTransfer) {
            return $this->executeUpdateShipmentMethod($shipmentMethodTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function executeUpdateShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        $idShipmentMethod = $shipmentMethodTransfer->getIdShipmentMethod();
        $hasShipmentMethod = $this->shipmentRepository->hasShipmentMethodByIdShipmentMethod($idShipmentMethod);

        if ($idShipmentMethod === null || $hasShipmentMethod === false) {
            return false;
        }

        $shipmentMethodTransfer = $this->shipmentEntityManager->updateShipmentMethod($shipmentMethodTransfer);
        $this->methodPrice->save($shipmentMethodTransfer);
        $this->storeRelationUpdater->update($shipmentMethodTransfer->getStoreRelation());

        return true;
    }
}
