<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentMethodDeleter implements ShipmentMethodDeleterInterface
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
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $shipmentEntityManager
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentEntityManagerInterface $shipmentEntityManager
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->shipmentEntityManager = $shipmentEntityManager;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function deleteShipmentMethod(int $idShipmentMethod): bool
    {
        $hasShipmentMethod = $this->shipmentRepository->hasShipmentMethodByIdShipmentMethod($idShipmentMethod);

        if ($hasShipmentMethod === false) {
            return false;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($idShipmentMethod): void {
            $this->executeDeleteShipmentMethodTransaction($idShipmentMethod);
        });

        return true;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return void
     */
    protected function executeDeleteShipmentMethodTransaction(int $idShipmentMethod): void
    {
        $this->shipmentEntityManager->deleteShipmentMethodStoreRelationsByIdShipmentMethod($idShipmentMethod);
        $this->shipmentEntityManager->deleteShipmentMethodPricesByIdShipmentMethod($idShipmentMethod);
        $this->shipmentEntityManager->deleteMethodByIdMethod($idShipmentMethod);
    }
}
