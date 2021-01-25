<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Writer;

use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Touch\Persistence\TouchEntityManagerInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Spryker\Zed\Touch\TouchConfig;

class TouchWriter implements TouchWriterInterface
{
    use TransactionTrait;

    /**
     * @uses \Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap::COL_ITEM_EVENT_DELETED
     */
    protected const COL_ITEM_EVENT_DELETED = 'deleted';

    /**
     * @uses \Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap::COL_ID_TOUCH
     */
    protected const COL_ID_TOUCH = 'spy_touch.id_touch';

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchEntityManagerInterface
     */
    protected $touchEntityManager;

    /**
     * @var \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected $utilDataReaderService;

    /**
     * @var \Spryker\Zed\Touch\TouchConfig
     */
    protected $touchConfig;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     * @param \Spryker\Zed\Touch\Persistence\TouchEntityManagerInterface $touchEntityManager
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\Touch\TouchConfig $touchConfig
     */
    public function __construct(
        TouchQueryContainerInterface $touchQueryContainer,
        TouchEntityManagerInterface $touchEntityManager,
        UtilDataReaderServiceInterface $utilDataReaderService,
        TouchConfig $touchConfig
    ) {
        $this->touchQueryContainer = $touchQueryContainer;
        $this->touchEntityManager = $touchEntityManager;
        $this->utilDataReaderService = $utilDataReaderService;
        $this->touchConfig = $touchConfig;
    }

    /**
     * @return int
     */
    public function cleanTouchEntitiesForDeletedItemEvent(): int
    {
        if (!$this->touchConfig->isTouchEnabled()) {
            return 0;
        }

        return $this->getTransactionHandler()->handleTransaction(function () {
            return $this->executeCleanTouchEntitiesForDeletedItemEventTransaction();
        });
    }

    /**
     * @return int
     */
    protected function executeCleanTouchEntitiesForDeletedItemEventTransaction(): int
    {
        $touchEntityIds = $this->getTouchEntityIdsForDeletedItemEvent(
            $this->touchQueryContainer->queryTouchListByItemEvent(static::COL_ITEM_EVENT_DELETED)
        );

        $deletedTouchEntitiesCount = $this->touchEntityManager->deleteTouchEntitiesByIds($touchEntityIds);
        $this->cleanTouchCollectorsDataByTouchIds($touchEntityIds);

        return $deletedTouchEntitiesCount;
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     *
     * @return int[]
     */
    protected function getTouchEntityIdsForDeletedItemEvent(SpyTouchQuery $touchQuery): array
    {
        $propelBatchIterator = $this->getTouchIdsToRemoveBatchCollection($touchQuery);

        $touchIds = [];
        foreach ($propelBatchIterator as $touchEntityIdsBatch) {
            $touchIds[] = $touchEntityIdsBatch->toArray();
        }

        if (!$touchIds) {
            return $touchIds;
        }

        return array_merge(...$touchIds);
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    protected function getTouchIdsToRemoveBatchCollection(SpyTouchQuery $touchQuery): CountableIteratorInterface
    {
        $touchIdsToRemoveQuery = $touchQuery->select(static::COL_ID_TOUCH);

        return $this->utilDataReaderService->getPropelBatchIterator($touchIdsToRemoveQuery);
    }

    /**
     * @param int[] $touchIds
     *
     * @return void
     */
    protected function cleanTouchCollectorsDataByTouchIds(array $touchIds): void
    {
        $this->touchEntityManager->deleteTouchSearchEntitiesByTouchIds($touchIds);
        $this->touchEntityManager->deleteTouchStorageEntitiesByTouchIds($touchIds);
    }
}
