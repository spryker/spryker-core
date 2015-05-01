<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Zed\Touch\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;

class TouchQueryContainer extends AbstractQueryContainer implements TouchQueryContainerInterface
{
    /**
     * @param string $itemType
     *
     * @return SpyTouchQuery
     */
    public function queryTouchListByItemType($itemType)
    {
        $query = SpyTouchQuery::create();
        $query->filterByItemType($itemType);

        return $query;
    }

    /**
     * @param string $itemType
     * @param string $itemId
     *
     * @return SpyTouchQuery
     */
    public function queryTouchEntry($itemType, $itemId)
    {
        $query = SpyTouchQuery::create();
        $query
            ->filterByItemType($itemType)
            ->filterByItemId($itemId);

        return $query;
    }
}
