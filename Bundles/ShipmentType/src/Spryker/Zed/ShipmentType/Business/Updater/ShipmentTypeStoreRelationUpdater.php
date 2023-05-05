<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface;
use Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface;

class ShipmentTypeStoreRelationUpdater implements ShipmentTypeStoreRelationUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface
     */
    protected ShipmentTypeRepositoryInterface $shipmentTypeRepository;

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
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface $shipmentTypeRepository
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface $shipmentTypeEntityManager
     * @param \Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface $storeDataExtractor
     */
    public function __construct(
        ShipmentTypeRepositoryInterface $shipmentTypeRepository,
        ShipmentTypeEntityManagerInterface $shipmentTypeEntityManager,
        ShipmentTypeToStoreFacadeInterface $storeFacade,
        StoreDataExtractorInterface $storeDataExtractor
    ) {
        $this->shipmentTypeRepository = $shipmentTypeRepository;
        $this->shipmentTypeEntityManager = $shipmentTypeEntityManager;
        $this->storeFacade = $storeFacade;
        $this->storeDataExtractor = $storeDataExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function updateShipmentTypeStoreRelations(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeTransfer
    {
        $requestedStoreRelationTransfer = $shipmentTypeTransfer->getStoreRelationOrFail();
        $requestedStoreNames = $this->storeDataExtractor->extractStoreNamesFromStoreTransfers($requestedStoreRelationTransfer->getStores());

        $requestedStoreTransfers = $this->storeFacade->getStoreTransfersByStoreNames($requestedStoreNames);
        $requestedStoreIds = $this->storeDataExtractor->extractStoreIdsFromStoreTransfers($requestedStoreTransfers);

        $existingStoreRelationTransfer = $this->shipmentTypeRepository->getShipmentTypeStoreRelationsIndexedByIdShipmentType([
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        ])[$shipmentTypeTransfer->getIdShipmentTypeOrFail()];
        $existingStoreIds = $this->storeDataExtractor->extractStoreIdsFromStoreTransfers($existingStoreRelationTransfer->getStores());

        $storeIdsToAssign = array_diff($requestedStoreIds, $existingStoreIds);
        $storeIdsToDeAssign = array_diff($existingStoreIds, $requestedStoreIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($shipmentTypeTransfer, $storeIdsToAssign, $storeIdsToDeAssign): void {
            $this->executeUpdateShipmentTypeStoreRelationsTransaction(
                $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
                $storeIdsToAssign,
                $storeIdsToDeAssign,
            );
        });

        return $shipmentTypeTransfer->setStoreRelation(
            $requestedStoreRelationTransfer
                ->setIdEntity($shipmentTypeTransfer->getIdShipmentTypeOrFail())
                ->setStores(new ArrayObject($requestedStoreTransfers)),
        );
    }

    /**
     * @param int $idShipmentType
     * @param list<int> $storeIdsToAssign
     * @param list<int> $storeIdsToDeAssign
     *
     * @return void
     */
    protected function executeUpdateShipmentTypeStoreRelationsTransaction(
        int $idShipmentType,
        array $storeIdsToAssign,
        array $storeIdsToDeAssign
    ): void {
        $this->shipmentTypeEntityManager->deleteShipmentTypeStoreRelations($idShipmentType, $storeIdsToDeAssign);
        $this->shipmentTypeEntityManager->createShipmentTypeStoreRelations($idShipmentType, $storeIdsToAssign);
    }
}
