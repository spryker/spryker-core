<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Writer;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface;

class ProductAbstractLabelStorageWriter implements ProductAbstractLabelStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT
     */
    protected const COL_PRODUCT_LABEL_PRODUCT_ABSTRACT_FK_PRODUCT_ABSTRACT = 'spy_product_label_product_abstract.fk_product_abstract';

    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelStoreTableMap::COL_FK_PRODUCT_ABSTRACT
     */
    protected const COL_PRODUCT_LABEL_STORE_FK_PRODUCT_LABEL = 'spy_product_label_store.fk_product_label';

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface
     */
    protected $productLabelStorageRepository;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface
     */
    protected $productLabelStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface $productLabelStorageRepository
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
     */
    public function __construct(
        ProductLabelStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductLabelStorageToProductLabelFacadeInterface $productLabelFacade,
        ProductLabelStorageRepositoryInterface $productLabelStorageRepository,
        ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productLabelFacade = $productLabelFacade;
        $this->productLabelStorageRepository = $productLabelStorageRepository;
        $this->productLabelStorageEntityManager = $productLabelStorageEntityManager;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\Writer\ProductAbstractLabelStorageWriter::writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents()} instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $this->writeCollection($productAbstractIds);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void
    {
        foreach ($productAbstractIds as $idProductAbstract) {
            $this->productLabelStorageEntityManager
                ->deleteProductAbstractLabelStorageByProductAbstractId($idProductAbstract);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents(array $eventTransfers): void
    {
        $productAbstractIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->writeCollection($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents(
        array $eventTransfers
    ): void {
        $productAbstractIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            static::COL_PRODUCT_LABEL_PRODUCT_ABSTRACT_FK_PRODUCT_ABSTRACT
        );

        $this->writeCollection($productAbstractIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    protected function writeCollection(array $productAbstractIds): void
    {
        $uniqueProductAbstractIds = array_unique($productAbstractIds);
        $productAbstractLabelStorageTransfers = $this->productLabelStorageRepository
            ->getProductAbstractLabelStorageTransfersByProductAbstractIds($uniqueProductAbstractIds);
        $indexedProductAbstractLabelStorageTransfers = $this->indexProductAbstractLabelTransfersByProductAbstractIds(
            $productAbstractLabelStorageTransfers
        );
        $groupedProductLabelIds = $this->getGroupedProductLabelIdsByProductAbstractIds($productAbstractIds);

        $this->storeData(
            $uniqueProductAbstractIds,
            $indexedProductAbstractLabelStorageTransfers,
            $groupedProductLabelIds
        );
    }

    /**
     * @param int[] $uniqueProductAbstractIds
     * @param \Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer[] $productAbstractLabelStorageTransfers
     * @param int[][] $productLabelIds
     *
     * @return void
     */
    protected function storeData(array $uniqueProductAbstractIds, array $productAbstractLabelStorageTransfers, array $productLabelIds): void
    {
        foreach ($uniqueProductAbstractIds as $productAbstractId) {
            if (isset($productAbstractLabelStorageTransfers[$productAbstractId])) {
                $this->storeDataSet($productAbstractId, $productLabelIds, $productAbstractLabelStorageTransfers[$productAbstractId]);

                continue;
            }

            $this->storeDataSet($productAbstractId, $productLabelIds);
        }
    }

    /**
     * @param int $productAbstractId
     * @param int[][] $productLabelIds
     * @param \Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer|null $productAbstractLabelStorageTransfer
     *
     * @return void
     */
    protected function storeDataSet(
        $productAbstractId,
        array $productLabelIds,
        ?ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer = null
    ): void {
        if (empty($productLabelIds[$productAbstractId])) {
            $this->productLabelStorageEntityManager->deleteProductAbstractLabelStorageByProductAbstractId(
                $productAbstractId
            );

            return;
        }

        if ($productAbstractLabelStorageTransfer === null) {
            $productAbstractLabelStorageTransfer = new ProductAbstractLabelStorageTransfer();
        }

        $productAbstractLabelStorageTransfer->setIdProductAbstract($productAbstractId);
        $productAbstractLabelStorageTransfer->setProductLabelIds($productLabelIds[$productAbstractId]);

        $this->productLabelStorageEntityManager->saveProductAbstractLabelStorage($productAbstractLabelStorageTransfer);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return int[][]
     */
    protected function getGroupedProductLabelIdsByProductAbstractIds(array $productAbstractIds): array
    {
        $productLabelProductAbstractTransfers = $this->productLabelFacade
            ->getProductLabelProductAbstractsByProductAbstractIds($productAbstractIds);

        return $this->getProductLabelIdsGroupedByProductAbstractIdsFromProductLabelProductAbstractTransfers(
            $productLabelProductAbstractTransfers
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[] $productLabelProductAbstractTransfers
     *
     * @return int[][]
     */
    protected function getProductLabelIdsGroupedByProductAbstractIdsFromProductLabelProductAbstractTransfers(
        array $productLabelProductAbstractTransfers
    ): array {
        $groupedLabelsByProductAbstractId = [];
        foreach ($productLabelProductAbstractTransfers as $productLabelProductAbstractTransfer) {
            $groupedLabelsByProductAbstractId[$productLabelProductAbstractTransfer->getFkProductAbstract()][] = $productLabelProductAbstractTransfer->getFkProductLabel();
        }

        return $groupedLabelsByProductAbstractId;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer[] $productAbstractLabelStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer[]
     */
    protected function indexProductAbstractLabelTransfersByProductAbstractIds(
        array $productAbstractLabelStorageTransfers
    ): array {
        $indexedProductAbstractLabelStorageTransfers = [];
        foreach ($productAbstractLabelStorageTransfers as $productAbstractLabelStorageTransfer) {
            $indexedProductAbstractLabelStorageTransfers[$productAbstractLabelStorageTransfer->getIdProductAbstract()] = $productAbstractLabelStorageTransfer;
        }

        return $indexedProductAbstractLabelStorageTransfers;
    }
}
