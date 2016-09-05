<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch\Filter;

use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;

class IdFilterUpdate extends AbstractIdFilter
{

    const CHUNK_SIZE = 250;

    /**
     * @param array $ids
     * @param string $itemType
     *
     * @return array
     */
    public function filter(array $ids, $itemType)
    {
        $filteredIds = [];
        $itemIdChunks = array_chunk($ids, self::CHUNK_SIZE);
        foreach ($itemIdChunks as $itemIdChunk) {
            $touchQuery = $this->touchQueryContainer->queryTouchEntriesByItemTypeAndItemIds($itemType, $itemIdChunk);
            $idCollection = $touchQuery->select([SpyTouchTableMap::COL_ITEM_ID])->find()->toArray();

            if (count($itemIdChunk) === count($idCollection)) {
                $filteredIds += $itemIdChunk;
            } else {
                $filteredIds += array_intersect($idCollection, $itemIdChunk);
            }
        }

        return $filteredIds;
    }

}
