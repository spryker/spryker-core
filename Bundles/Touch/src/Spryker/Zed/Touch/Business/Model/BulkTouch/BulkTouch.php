<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch;

use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouch;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

class BulkTouch implements BulkTouchInterface
{

    const BULK_UPDATE_CHUNK_SIZE = 250;
    const TYPE_INSERT = 'insert';
    const TYPE_UPDATE = 'update';

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     */
    public function __construct(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouch($itemType, $itemEvent, array $itemIds)
    {
        $affectedRows = 0;
        $affectedRows += $this->update($itemType, $itemEvent, $itemIds);
        $affectedRows += $this->insert($itemType, $itemEvent, $itemIds);

        return $affectedRows;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return int
     */
    protected function update($itemType, $itemEvent, array $itemIds)
    {
        $updated = 0;
        $itemIds = $this->filterIds($itemType, $itemIds, self::TYPE_UPDATE);
        $itemIdChunks = array_chunk($itemIds, self::BULK_UPDATE_CHUNK_SIZE);
        $itemEvent = $this->getItemEventValueFor($itemEvent);

        foreach ($itemIdChunks as $itemIdChunk) {
            $touchQuery = $this->touchQueryContainer->queryTouchEntriesByItemTypeAndItemIds($itemType, $itemIdChunk);
            $updated += $touchQuery->update([
                $this->getTouchedColumnName() => new \DateTime(),
                $this->getItemEventColumnName() => $itemEvent,
            ]);
        }

        return $updated;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @throws \Exception
     * @return int
     */
    protected function insert($itemType, $itemEvent, array $itemIds)
    {
        $itemIds = $this->filterIds($itemType, $itemIds, self::TYPE_INSERT);
        $propelCollection = new ObjectCollection();
        $propelCollection->setModel(SpyTouch::class);

        foreach ($itemIds as $itemId) {
            $touchEntity = new SpyTouch();
            $touchEntity->setItemEvent($itemEvent)
                ->setItemId($itemId)
                ->setItemType($itemType)
                ->setTouched(new \DateTime());

            $propelCollection->append($touchEntity);
        }

        $propelCollection->save();

        return $propelCollection->count();
    }

    /**
     * @param string $itemType
     * @param array $itemIds
     * @param string $method
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return array
     */
    protected function filterIds($itemType, array $itemIds, $method)
    {
        $ids = [];
        $itemIdChunks = array_chunk($itemIds, self::BULK_UPDATE_CHUNK_SIZE);
        foreach ($itemIdChunks as $itemIdChunk) {
            $touchQuery = $this->touchQueryContainer->queryTouchEntriesByItemTypeAndItemIds($itemType, $itemIdChunk);
            $idCollection = $touchQuery->select([SpyTouchTableMap::COL_ITEM_ID])->find()->toArray();

            if ($method === self::TYPE_INSERT) {
                $ids += array_diff($itemIdChunk, $idCollection);
            }

            if ($method === self::TYPE_UPDATE) {
                if (count($itemIdChunk) === count($idCollection)) {
                    $ids += $itemIdChunk;
                } else {
                    $ids += array_intersect($idCollection, $itemIdChunk);
                }
            }
        }

        return $ids;
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return string
     */
    protected function getTouchedColumnName()
    {
        return SpyTouchTableMap::translateFieldName(SpyTouchTableMap::COL_TOUCHED, SpyTouchTableMap::TYPE_COLNAME, SpyTouchTableMap::TYPE_PHPNAME);
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return string
     */
    protected function getItemEventColumnName()
    {
        return SpyTouchTableMap::translateFieldName(SpyTouchTableMap::COL_ITEM_EVENT, SpyTouchTableMap::TYPE_COLNAME, SpyTouchTableMap::TYPE_PHPNAME);
    }

    /**
     * @param string $eventName
     *
     * @return string
     */
    protected function getItemEventValueFor($eventName)
    {
        $itemEventValueSet = SpyTouchTableMap::getValueSet(SpyTouchTableMap::COL_ITEM_EVENT);

        return array_search($eventName, $itemEventValueSet);
    }

}
