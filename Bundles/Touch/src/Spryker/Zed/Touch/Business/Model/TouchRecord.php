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
use Spryker\Shared\Library\BatchIterator\Builder\BatchIteratorBuilderInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

class TouchRecord implements TouchRecordInterface
{

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @var \Spryker\Shared\Library\BatchIterator\Builder\BatchIteratorBuilderInterface
     */
    protected $batchIteratorBuilder;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     * @param \Spryker\Shared\Library\BatchIterator\Builder\BatchIteratorBuilderInterface $batchIteratorBuilder
     */
    public function __construct(
        TouchQueryContainerInterface $queryContainer,
        ConnectionInterface $connection,
        BatchIteratorBuilderInterface $batchIteratorBuilder
    ) {

        $this->touchQueryContainer = $queryContainer;
        $this->connection = $connection;
        $this->batchIteratorBuilder = $batchIteratorBuilder;
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
     * @throws \Exception
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
        } catch (\Throwable $exception) {
            $this->touchQueryContainer->getConnection()->rollBack();
            throw $exception;
        } catch (\Exception $exception) {
            $this->touchQueryContainer->getConnection()->rollBack();
            throw $exception;
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

        /* @var $batch \Propel\Runtime\Collection\ArrayCollection */
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
     * @return \Spryker\Shared\Library\BatchIterator\PropelBatchIterator
     */
    protected function getTouchIdsToRemoveBatchCollection(SpyTouchQuery $query)
    {
        $touchIdsToRemoveQuery = $query->select(SpyTouchTableMap::COL_ID_TOUCH);

        return $this->batchIteratorBuilder->buildPropelBatchIterator($touchIdsToRemoveQuery);
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
