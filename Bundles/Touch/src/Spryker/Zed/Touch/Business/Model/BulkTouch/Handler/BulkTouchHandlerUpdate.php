<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch\Handler;

use DateTime;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouch;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class BulkTouchHandlerUpdate extends AbstractBulkTouchHandler
{
    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouch($itemType, $itemEvent, array $itemIds)
    {
        $updated = 0;
        $itemIds = $this->filter->filter($itemIds, $itemType);
        $itemIdChunks = array_chunk($itemIds, self::BULK_UPDATE_CHUNK_SIZE);

        foreach ($itemIdChunks as $itemIdChunk) {
            $touchEntities = $this->touchQueryContainer
                ->queryTouchEntriesByItemTypeAndItemIdsAllowableToUpdateWithItemEvent($itemType, $itemEvent, $itemIdChunk)
                ->find();
            foreach ($touchEntities as $touchEntity) {
                $this->updateTouchEntityWithItemEvent($touchEntity, $itemEvent);

                $updated++;
            }
        }

        return $updated;
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouch $touchEntity
     * @param string $itemEvent
     *
     * @return void
     */
    protected function updateTouchEntityWithItemEvent(SpyTouch $touchEntity, string $itemEvent): void
    {
        $touchEntity->setTouched(new DateTime());
        $touchEntity->setItemEvent($itemEvent);
        $touchEntity->save();
    }

    /**
     * @return string
     */
    protected function getTouchedColumnName()
    {
        return SpyTouchTableMap::translateFieldName(SpyTouchTableMap::COL_TOUCHED, SpyTouchTableMap::TYPE_COLNAME, SpyTouchTableMap::TYPE_PHPNAME);
    }

    /**
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
