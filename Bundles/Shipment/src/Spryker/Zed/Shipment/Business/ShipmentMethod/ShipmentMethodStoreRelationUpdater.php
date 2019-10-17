<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

class ShipmentMethodStoreRelationUpdater implements ShipmentMethodStoreRelationUpdaterInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationReaderInterface
     */
    protected $storeRelationReader;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationReaderInterface $storeRelationReader
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     */
    public function __construct(
        ShipmentMethodStoreRelationReaderInterface $storeRelationReader,
        ShipmentEntityManagerInterface $entityManager
    ) {
        $this->storeRelationReader = $storeRelationReader;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireIdEntity();

        $currentIdStores = $this->getIdStoresByIdCmsPage($storeRelationTransfer->getIdEntity());
        $requestedIdStores = $storeRelationTransfer->getIdStores() ?? [];

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);

        $this->entityManager->addStoreRelations($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->entityManager->removeStoreRelations($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return int[]
     */
    protected function getIdStoresByIdCmsPage(int $idShipmentMethod): array
    {
        $storeRelation = $this->storeRelationReader->getStoreRelation(
            (new StoreRelationTransfer())->setIdEntity($idShipmentMethod)
        );

        return $storeRelation->getIdStores();
    }
}
