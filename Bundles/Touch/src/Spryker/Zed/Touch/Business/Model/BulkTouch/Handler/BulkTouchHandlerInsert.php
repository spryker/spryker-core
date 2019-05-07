<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch\Handler;

use DateTime;
use Orm\Zed\Touch\Persistence\SpyTouch;
use Propel\Runtime\Collection\ObjectCollection;

class BulkTouchHandlerInsert extends AbstractBulkTouchHandler
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
        $itemIds = $this->filter->filter($itemIds, $itemType);
        $itemIdChunks = array_chunk($itemIds, self::BULK_UPDATE_CHUNK_SIZE);

        $affectedRows = 0;
        foreach ($itemIdChunks as $itemIdChunk) {
            $affectedRows += $this->insertChunk($itemType, $itemEvent, $itemIdChunk);
        }

        return $affectedRows;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return int
     */
    protected function insertChunk($itemType, $itemEvent, array $itemIds)
    {
        $propelCollection = new ObjectCollection();
        $propelCollection->setModel(SpyTouch::class);

        foreach ($itemIds as $itemId) {
            $touchEntity = new SpyTouch();
            $touchEntity->setItemEvent($itemEvent)
                ->setItemId($itemId)
                ->setItemType($itemType)
                ->setTouched(new DateTime());

            $propelCollection->append($touchEntity);
        }

        $propelCollection->save();

        return $propelCollection->count();
    }
}
