<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Writer;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage;
use Spryker\ProductLabelStorage\src\Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelProductAbstractMapper;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface;

class ProductLabelStorageWriter implements ProductLabelStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;
    /**
     * @var ProductLabelStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;
    /**
     * @var ProductLabelStorageRepositoryInterface
     */
    protected $productLabelStorageRepository;
    /**
     * @var ProductLabelProductAbstractMapper
     */
    protected $productLabelProductAbstractMapper;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     * @param ProductLabelStorageToEventBehaviorFacadeInterface $productLabelStorageToEventBehaviorFacade
     * @param ProductLabelStorageRepositoryInterface $productLabelStorageRepository
     * @param ProductLabelProductAbstractMapper $productLabelProductAbstractMapper
     */
    public function __construct(
        ProductLabelStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue,
        ProductLabelStorageToEventBehaviorFacadeInterface $productLabelStorageToEventBehaviorFacade,
        ProductLabelStorageRepositoryInterface $productLabelStorageRepository,
        ProductLabelProductAbstractMapper $productLabelProductAbstractMapper
    )
    {
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->eventBehaviorFacade = $productLabelStorageToEventBehaviorFacade;
        $this->productLabelStorageRepository = $productLabelStorageRepository;
        $this->productLabelProductAbstractMapper = $productLabelProductAbstractMapper;
    }

    /**
     * @deprecated
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $uniqueProductAbstractIds = $this->productLabelStorageRepository
            ->getUniqueProductAbstractIdsFromLocalizedAttributesByProductAbstractIds($productAbstractIds);

        $groupedLabelsByProductAbstractId = $this->prepareGroupedLabelsByProductAbstractId($productAbstractIds);

        $spyProductAbstractLabelStorageEntities = $this->findProductAbstractLabelStorageEntitiesByProductAbstractIds($productAbstractIds);

        $this->storeData($uniqueProductAbstractIds, $spyProductAbstractLabelStorageEntities, $groupedLabelsByProductAbstractId);
    }

    /**
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeProductLabelStorageCollectionByProductAbstractEvents(array $eventTransfers): void
    {
        $productAbstractIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $uniqueProductAbstractIds = $this->productLabelStorageRepository
            ->getUniqueProductAbstractIdsFromLocalizedAttributesByProductAbstractIds($productAbstractIds);

        $groupedLabelsByProductAbstractId = $this->getGroupedProductLabelIdsByProductAbstractIds($productAbstractIds);

        $spyProductAbstractLabelStorageEntities = $this->findProductAbstractLabelStorageEntitiesByProductAbstractIds($productAbstractIds);

        $this->storeData($uniqueProductAbstractIds, $spyProductAbstractLabelStorageEntities, $groupedLabelsByProductAbstractId);
    }

    /**
     * @param array $uniqueProductAbstractIds
     * @param array $spyProductAbstractLabelStorageEntities
     * @param array $productLabelsIds
     *
     * @return void
     */
    protected function storeData(array $uniqueProductAbstractIds, array $spyProductAbstractLabelStorageEntities, array $productLabelsIds)
    {
        foreach ($uniqueProductAbstractIds as $productAbstractId) {
            if (isset($spyProductAbstractLabelStorageEntities[$productAbstractId])) {
                $this->storeDataSet($productAbstractId, $productLabelsIds, $spyProductAbstractLabelStorageEntities[$productAbstractId]);

                continue;
            }

            $this->storeDataSet($productAbstractId, $productLabelsIds);
        }
    }

    /**
     * @param int $productAbstractId
     * @param array $productLabelsIds
     * @param \Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage|null $spyProductAbstractLabelStorageEntity
     *
     * @return void
     */
    protected function storeDataSet($productAbstractId, array $productLabelsIds, ?SpyProductAbstractLabelStorage $spyProductAbstractLabelStorageEntity = null)
    {
        if ($spyProductAbstractLabelStorageEntity === null) {
            $spyProductAbstractLabelStorageEntity = new SpyProductAbstractLabelStorage();
        }

        if (empty($productLabelsIds[$productAbstractId])) {
            if (!$spyProductAbstractLabelStorageEntity->isNew()) {
                $spyProductAbstractLabelStorageEntity->delete();
            }

            return;
        }

        $productAbstractLabelStorageTransfer = new ProductAbstractLabelStorageTransfer();
        $productAbstractLabelStorageTransfer->setIdProductAbstract($productAbstractId);
        $productAbstractLabelStorageTransfer->setProductLabelIds($productLabelsIds[$productAbstractId]);

        $spyProductAbstractLabelStorageEntity->setFkProductAbstract($productAbstractId);
        $spyProductAbstractLabelStorageEntity->setData($productAbstractLabelStorageTransfer->toArray());
        $spyProductAbstractLabelStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductAbstractLabelStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return int[][]
     */
    protected function getGroupedProductLabelIdsByProductAbstractIds(array $productAbstractIds): array
    {
        $productLabelProductAbstractTransferCollection = $this->productLabelStorageRepository
            ->getProductLabelProductAbstractTransferCollectionByProductAbstractIds($productAbstractIds);

        return $this->productLabelProductAbstractMapper
            ->mapProductLabelProductAbstractTransferCollectionToProductLabelIdsGroupedByProductAbstractIds(
                $productLabelProductAbstractTransferCollection
            );
    }

    /**
     * @param array $productAbstractIds
     */
    public function deleteStorageData(array $productAbstractIds): void
    {
        $spyProductAbstractLabelStorageEntities = $this->findProductAbstractLabelStorageEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($spyProductAbstractLabelStorageEntities as $spyProductAbstractLabelStorageEntity) {
            $spyProductAbstractLabelStorageEntity->delete();
        }
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[]
     */
    protected function findProductLabelAbstractEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductLabelProductAbstractByProductAbstractIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLabelStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractLabelStorageEntities = $this->queryContainer->queryProductAbstractLabelStorageByIds($productAbstractIds)->find();
        $productAbstractStorageLabelEntitiesById = [];
        foreach ($productAbstractLabelStorageEntities as $productAbstractLabelStorageEntity) {
            $productAbstractStorageLabelEntitiesById[$productAbstractLabelStorageEntity->getFkProductAbstract()] = $productAbstractLabelStorageEntity;
        }

        return $productAbstractStorageLabelEntitiesById;
    }
}
