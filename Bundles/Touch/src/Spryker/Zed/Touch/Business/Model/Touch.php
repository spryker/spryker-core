<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model;

use DateTime;
use Generated\Shared\Transfer\TouchTransfer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

class Touch implements TouchInterface
{
    const BULK_UPDATE_CHUNK_SIZE = 250;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     */
    public function __construct(TouchQueryContainerInterface $queryContainer)
    {
        $this->touchQueryContainer = $queryContainer;
    }

    /**
     * @deprecated Use `Spryker\Zed\Touch\Business\Model\BulkTouch\BulkTouch::bulkTouch()` instead
     *
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkUpdateTouchRecords($itemType, $itemEvent, array $itemIds = [])
    {
        $updated = 0;
        $itemIdChunks = array_chunk($itemIds, self::BULK_UPDATE_CHUNK_SIZE);
        foreach ($itemIdChunks as $itemIdChunk) {
            $touchQuery = $this->touchQueryContainer->queryTouchEntries($itemType, $itemEvent, $itemIdChunk);
            $updated += $touchQuery->update(['Touched' => new DateTime()]);
        }

        return $updated;
    }

    /**
     * @param string $itemType
     *
     * @return \Generated\Shared\Transfer\TouchTransfer[]
     */
    public function getItemsByType($itemType)
    {
        $entityList = $this->touchQueryContainer
            ->queryTouchListByItemType($itemType)
            ->find();

        $items = [];
        foreach ($entityList as $entity) {
            $touchTransfer = (new TouchTransfer())
                ->fromArray($entity->toArray());

            $items[$entity->getIdTouch()] = $touchTransfer;
        }

        return $items;
    }
}
