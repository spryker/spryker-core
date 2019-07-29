<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch\Filter;

class IdFilterUpdate extends AbstractIdFilter
{
    public const CHUNK_SIZE = 250;

    /**
     * @param array $ids
     * @param string $itemType
     *
     * @return array
     */
    public function filter(array $ids, string $itemType): array
    {
        $filteredIds = [];
        $itemIdChunks = array_chunk($ids, self::CHUNK_SIZE);
        foreach ($itemIdChunks as $itemIdChunk) {
            $idCollection = $this->getIdCollection($itemType, $itemIdChunk);

            if (count($itemIdChunk) === count($idCollection)) {
                $filteredIds = array_merge($filteredIds, $itemIdChunk);
            } else {
                $filteredIds = array_merge($filteredIds, array_intersect($idCollection, $itemIdChunk));
            }
        }

        return $filteredIds;
    }
}
