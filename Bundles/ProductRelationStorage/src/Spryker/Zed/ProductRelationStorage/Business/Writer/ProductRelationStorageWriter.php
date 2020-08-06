<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;
use Generated\Shared\Transfer\ProductRelationStorageTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationStoreTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Spryker\Zed\ProductRelationStorage\Business\Grouper\ProductRelationStorageGrouperInterface;
use Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface;
use Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageEntityManagerInterface;
use Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface;

class ProductRelationStorageWriter implements ProductRelationStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface
     */
    protected $productRelationStorageRepository;

    /**
     * @var \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @var \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageEntityManagerInterface
     */
    protected $productRelationStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductRelationStorage\Business\Grouper\ProductRelationStorageGrouperInterface
     */
    protected $productRelationStorageGrouper;

    /**
     * @param \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface $productRelationStorageRepository
     * @param \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface $productRelationFacade
     * @param \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageEntityManagerInterface $productRelationStorageEntityManager
     * @param \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductRelationStorage\Business\Grouper\ProductRelationStorageGrouperInterface $productRelationStorageGrouper
     */
    public function __construct(
        ProductRelationStorageRepositoryInterface $productRelationStorageRepository,
        ProductRelationStorageToProductRelationFacadeInterface $productRelationFacade,
        ProductRelationStorageEntityManagerInterface $productRelationStorageEntityManager,
        ProductRelationStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductRelationStorageGrouperInterface $productRelationStorageGrouper
    ) {
        $this->productRelationStorageRepository = $productRelationStorageRepository;
        $this->productRelationFacade = $productRelationFacade;
        $this->productRelationStorageEntityManager = $productRelationStorageEntityManager;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productRelationStorageGrouper = $productRelationStorageGrouper;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductRelationStorageCollectionByProductRelationStoreEvents(
        array $eventTransfers
    ): void {
        $productRelationIds = $this->eventBehaviorFacade
            ->getEventTransferForeignKeys($eventTransfers, SpyProductRelationStoreTableMap::COL_FK_PRODUCT_RELATION);
        $productAbstractIds = $this->productRelationFacade->getProductAbstractIdsByProductRelationIds($productRelationIds);

        $this->writeCollection($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductRelationStorageCollectionByProductRelationPublishingEvents(
        array $eventTransfers
    ): void {
        $productAbstractIds = $this->eventBehaviorFacade
            ->getEventTransferIds($eventTransfers);

        $this->writeCollection($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductRelationStorageCollectionByProductRelationEvents(array $eventTransfers): void
    {
        $productAbstractIds = $this->eventBehaviorFacade
            ->getEventTransferForeignKeys($eventTransfers, SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT);

        $this->writeCollection($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductRelationStorageCollectionByProductRelationProductAbstractEvents(
        array $eventTransfers
    ): void {
        $productAbstractIds = $this->eventBehaviorFacade
            ->getEventTransferForeignKeys($eventTransfers, SpyProductRelationProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT);

        $this->writeCollection($productAbstractIds);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationPublishingEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationStoreEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationProductAbstractEvents()}
     *   instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->writeCollection($productAbstractIds);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationPublishingEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationStoreEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationProductAbstractEvents()}
     *   instead.
     *
     * @see \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationPublishingEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationStoreEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationEvents()
     * @see \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationProductAbstractEvents()
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->productRelationStorageEntityManager->deleteProductAbstractStorageEntitiesByProductAbstractIds($productAbstractIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    protected function writeCollection(array $productAbstractIds): void
    {
        $productRelationTransfers = $this->productRelationFacade
            ->getProductRelationsByProductAbstractIds($productAbstractIds);

        $productRelations = $this->productRelationStorageGrouper
            ->groupProductRelationsByProductAbstractAndStore($productRelationTransfers);

        if ($productRelations === []) {
            $this->productRelationStorageEntityManager->deleteProductAbstractStorageEntitiesByProductAbstractIds($productAbstractIds);

            return;
        }

        $this->storeData($productRelations);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer[][][] $productRelations
     *
     * @return void
     */
    protected function storeData(array $productRelations)
    {
        foreach ($productRelations as $idProductAbstract => $productRelationTransfersByStore) {
            $this->deleteEmptyRows($idProductAbstract, $productRelationTransfersByStore);
            foreach ($productRelationTransfersByStore as $store => $productRelationTransfers) {
                $this->storeDataSet($idProductAbstract, $store, $productRelationTransfers);
            }
        }
    }

    /**
     * @param int $idProductAbstract
     * @param array $productRelationTransfersByStore
     *
     * @return void
     */
    protected function deleteEmptyRows(
        int $idProductAbstract,
        array $productRelationTransfersByStore
    ): void {
        $storesFromStorage = $this->productRelationStorageRepository
            ->getStoresByIdProductAbstractFromStorage($idProductAbstract);
        $storesFromProductRelations = array_keys($productRelationTransfersByStore);
        $storesDiff = array_diff($storesFromStorage, $storesFromProductRelations);

        if ($storesDiff !== []) {
            $this->productRelationStorageEntityManager
                ->deleteProductAbstractRelationStorageEntitiesByProductAbstractIdAndStores($idProductAbstract, $storesDiff);
        }
    }

    /**
     * @param int $idProductAbstract
     * @param string $store
     * @param \Generated\Shared\Transfer\ProductRelationTransfer[] $productRelationTransfers
     *
     * @return void
     */
    protected function storeDataSet(
        int $idProductAbstract,
        string $store,
        array $productRelationTransfers
    ) {
        $productRelationStorageTransfers = $this->fillProductRelationStorageTransfers($productRelationTransfers, $store);

        $productAbstractRelationStorageTransfer = new ProductAbstractRelationStorageTransfer();
        $productAbstractRelationStorageTransfer->setIdProductAbstract($idProductAbstract);
        $productAbstractRelationStorageTransfer->setProductRelations($productRelationStorageTransfers);
        $productAbstractRelationStorageTransfer->setStore($store);

        $this->productRelationStorageEntityManager->saveProductAbstractRelationStorageEntity(
            $idProductAbstract,
            $productAbstractRelationStorageTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer[] $productRelations
     * @param string $store
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductRelationStorageTransfer[]
     */
    protected function fillProductRelationStorageTransfers(array $productRelations, string $store)
    {
        $productRelationStorageTransfers = new ArrayObject();

        foreach ($productRelations as $productRelationTransfer) {
            $storeNames = $this->getStoreNamesFromStoreRelationTransfer($productRelationTransfer->getStoreRelation());

            if (!in_array($store, $storeNames)) {
                continue;
            }

            $productRelationStorageTransfer = new ProductRelationStorageTransfer();
            $productRelationStorageTransfer->setIsActive($productRelationTransfer->getIsActive());
            $productRelationStorageTransfer->setKey($productRelationTransfer->getProductRelationType()->getKey());
            $productRelationStorageTransfer->setProductAbstractIds($this->fillProductAbstractIds($productRelationTransfer));
            $productRelationStorageTransfers->append($productRelationStorageTransfer);
        }

        return $productRelationStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int[]
     */
    protected function fillProductAbstractIds(
        ProductRelationTransfer $productRelationTransfer
    ): array {
        $productAbstractIds = [];

        foreach ($productRelationTransfer->getRelatedProducts() as $productRelationRelatedProductTransfer) {
            $productAbstractIds[$productRelationRelatedProductTransfer->getFkProductAbstract()] = $productRelationRelatedProductTransfer->getOrder();
        }

        return $productAbstractIds;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return string[]
     */
    protected function getStoreNamesFromStoreRelationTransfer(
        StoreRelationTransfer $storeRelationTransfer
    ): array {
        $storeNames = [];

        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeNames[] = $storeTransfer->getName();
        }

        return $storeNames;
    }
}
