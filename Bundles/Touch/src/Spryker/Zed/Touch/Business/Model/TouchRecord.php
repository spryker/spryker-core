<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model;

use DateTime;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouch;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Throwable;

class TouchRecord implements TouchRecordInterface
{
    /**
     * @var \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected $utilDataReaderService;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        TouchQueryContainerInterface $queryContainer,
        ConnectionInterface $connection
    ) {
        $this->utilDataReaderService = $utilDataReaderService;
        $this->touchQueryContainer = $queryContainer;
        $this->connection = $connection;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param int $idItem
     * @param bool $keyChange
     *
     * @return bool
     */
    public function saveTouchRecord(
        $itemType,
        $itemEvent,
        $idItem,
        $keyChange = false
    ) {
        $this->connection->beginTransaction();

        if ($keyChange) {
            $this->insertKeyChangeRecord($itemType, $idItem);

            if ($itemEvent === SpyTouchTableMap::COL_ITEM_EVENT_DELETED) {
                if (!$this->deleteKeyChangeActiveRecord($itemType, $idItem)) {
                    $this->insertTouchRecord(
                        $itemType,
                        $itemEvent,
                        $idItem,
                        SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE
                    );
                }
            } else {
                $this->insertTouchRecord($itemType, $itemEvent, $idItem);
            }
        } else {
            $touchEntity = $this->touchQueryContainer->queryUpdateTouchEntry(
                $itemType,
                $idItem
            )->findOneOrCreate();

            $this->saveTouchEntity($itemType, $idItem, $itemEvent, $touchEntity);
        }

        $this->connection->commit();

        return true;
    }

    /**
     * @param string $itemType
     * @param int $idItem
     * @param string $itemEvent
     * @param \Orm\Zed\Touch\Persistence\SpyTouch $touchEntity
     *
     * @return void
     */
    protected function saveTouchEntity(
        $itemType,
        $idItem,
        $itemEvent,
        SpyTouch $touchEntity
    ) {
        $touchEntity->setItemType($itemType)
            ->setItemEvent($itemEvent)
            ->setItemId($idItem)
            ->setTouched(new DateTime());
        $touchEntity->save();
    }

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    protected function deleteKeyChangeActiveRecord($itemType, $idItem)
    {
        $touchDeletedEntity = $this->touchQueryContainer
            ->queryUpdateTouchEntry(
                $itemType,
                $idItem,
                SpyTouchTableMap::COL_ITEM_EVENT_DELETED
            )
            ->findOne();

        if ($touchDeletedEntity === null) {
            return false;
        }

        $touchActiveEntity = $this->touchQueryContainer
            ->queryUpdateTouchEntry(
                $itemType,
                $idItem,
                SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE
            )
            ->findOne();

        if ($touchActiveEntity !== null) {
            $touchActiveEntity->delete();
        }

        return true;
    }

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return void
     */
    protected function insertKeyChangeRecord($itemType, $idItem)
    {
        $touchOldEntity = $this->touchQueryContainer
            ->queryUpdateTouchEntry(
                $itemType,
                $idItem,
                SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE
            )
            ->findOne();

        if ($touchOldEntity === null) {
            return;
        }

        $touchDeletedEntity = $this->touchQueryContainer
            ->queryUpdateTouchEntry(
                $itemType,
                $idItem,
                SpyTouchTableMap::COL_ITEM_EVENT_DELETED
            )
            ->findOne();

        if ($touchDeletedEntity === null) {
            $this->saveTouchEntity(
                $itemType,
                $idItem,
                SpyTouchTableMap::COL_ITEM_EVENT_DELETED,
                $touchOldEntity
            );
        }
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param int $idItem
     * @param string|null $type
     *
     * @return void
     */
    protected function insertTouchRecord(
        $itemType,
        $itemEvent,
        $idItem,
        $type = null
    ) {
        if ($type === null) {
            $type = $itemEvent;
        }

        $touchEntity = $this->touchQueryContainer->queryUpdateTouchEntry(
            $itemType,
            $idItem,
            $type
        )->findOneOrCreate();

        $this->saveTouchEntity($itemType, $idItem, $itemEvent, $touchEntity);
    }

    /**
     * Removes all the rows from the touch table(s)
     * which are marked as deleted (SpyTouchTableMap::COL_ITEM_EVENT_DELETED).
     * Returns the number of Touch entries deleted.
     *
     * @api
     *
     * @throws \Throwable
     *
     * @return int
     */
    public function removeTouchEntriesMarkedAsDeleted()
    {
        $this->touchQueryContainer->getConnection()->beginTransaction();

        try {
            $touchListQuery = $this->touchQueryContainer
                ->queryTouchListByItemEvent(
                    SpyTouchTableMap::COL_ITEM_EVENT_DELETED
                );
            $deletedCount = $this->removeTouchEntries($touchListQuery);
        } catch (Throwable $throwable) {
            $this->touchQueryContainer->getConnection()->rollBack();
            throw $throwable;
        }

        $this->touchQueryContainer->getConnection()->commit();

        return $deletedCount;
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $query
     *
     * @return int
     */
    protected function removeTouchEntries(SpyTouchQuery $query)
    {
        $deletedCount = 0;
        $batchCollection = $this->getTouchIdsToRemoveBatchCollection($query);

        /** @var \Propel\Runtime\Collection\ArrayCollection $batch */
        foreach ($batchCollection as $batch) {
            $touchIdsToRemove = $batch->toArray();
            $this->removeTouchDataForCollectors($touchIdsToRemove);
            $deletedCount += $query
                ->filterByIdTouch($touchIdsToRemove, Criteria::IN)
                ->delete();
        }

        return $deletedCount;
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $query
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    protected function getTouchIdsToRemoveBatchCollection(SpyTouchQuery $query)
    {
        $touchIdsToRemoveQuery = $query->select(SpyTouchTableMap::COL_ID_TOUCH);

        return $this->utilDataReaderService->getPropelBatchIterator($touchIdsToRemoveQuery);
    }

    /**
     * Removes Touch data in any of the database tables for Collectors
     * If a different Collector table is added to the system, this code should
     * be updated or overridden to include covering that table as well
     *
     * @param array $touchIds
     *
     * @return void
     */
    protected function removeTouchDataForCollectors(array $touchIds)
    {
        $this->touchQueryContainer
            ->queryTouchSearchByTouchIds($touchIds)
            ->delete();

        $this->touchQueryContainer
            ->queryTouchStorageByTouchIds($touchIds)
            ->delete();
    }
}
