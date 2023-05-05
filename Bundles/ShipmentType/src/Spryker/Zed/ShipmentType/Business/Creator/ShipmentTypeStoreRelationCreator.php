<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface;
use Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface;

class ShipmentTypeStoreRelationCreator implements ShipmentTypeStoreRelationCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface
     */
    protected ShipmentTypeEntityManagerInterface $shipmentTypeEntityManager;

    /**
     * @var \Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface
     */
    protected ShipmentTypeToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface
     */
    protected StoreDataExtractorInterface $storeDataExtractor;

    /**
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface $shipmentTypeEntityManager
     * @param \Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface $storeDataExtractor
     */
    public function __construct(
        ShipmentTypeEntityManagerInterface $shipmentTypeEntityManager,
        ShipmentTypeToStoreFacadeInterface $storeFacade,
        StoreDataExtractorInterface $storeDataExtractor
    ) {
        $this->shipmentTypeEntityManager = $shipmentTypeEntityManager;
        $this->storeFacade = $storeFacade;
        $this->storeDataExtractor = $storeDataExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function createShipmentTypeStoreRelations(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeTransfer
    {
        $storeRelationTransfer = $shipmentTypeTransfer->getStoreRelationOrFail();
        $storeNames = $this->storeDataExtractor->extractStoreNamesFromStoreTransfers($storeRelationTransfer->getStores());

        $storeTransfers = $this->storeFacade->getStoreTransfersByStoreNames($storeNames);
        $storeIds = $this->storeDataExtractor->extractStoreIdsFromStoreTransfers($storeTransfers);

        $this->getTransactionHandler()->handleTransaction(function () use ($shipmentTypeTransfer, $storeIds): void {
            $this->executeCreateShipmentTypeStoreRelationsTransaction($shipmentTypeTransfer->getIdShipmentTypeOrFail(), $storeIds);
        });

        return $shipmentTypeTransfer->setStoreRelation(
            $storeRelationTransfer
                ->setIdEntity($shipmentTypeTransfer->getIdShipmentTypeOrFail())
                ->setStores(new ArrayObject($storeTransfers)),
        );
    }

    /**
     * @param int $idShipmentType
     * @param list<int> $storeIds
     *
     * @return void
     */
    protected function executeCreateShipmentTypeStoreRelationsTransaction(int $idShipmentType, array $storeIds): void
    {
        $this->shipmentTypeEntityManager->createShipmentTypeStoreRelations($idShipmentType, $storeIds);
    }
}
