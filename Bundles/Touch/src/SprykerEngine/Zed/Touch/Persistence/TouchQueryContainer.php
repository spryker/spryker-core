<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;

class TouchQueryContainer extends AbstractQueryContainer implements TouchQueryContainerInterface
{
    const TOUCH_ENTRY_QUERY_KEY = 'search touch entry';

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
            ->setQueryKey(self::TOUCH_ENTRY_QUERY_KEY)
            ->filterByItemType($itemType)
            ->filterByItemId($itemId);

        return $query;
    }

    /**
     * @param string $itemType
     * @param \DateTime $lastTouchedAt
     *
     * @return SpyTouchQuery
     */
    public function createBasicExportableQuery($itemType, \DateTime $lastTouchedAt)
    {
        $query = SpyTouchQuery::create();
        $query
            ->filterByItemType($itemType)
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->filterByTouched(['min' => $lastTouchedAt])
        ;

        return $query;
    }

    /**
     * @return SpyTouchQuery
     */
    public function queryExportTypes()
    {
        $query = SpyTouchQuery::create();
        $query
            ->addSelectColumn(SpyTouchTableMap::COL_ITEM_TYPE)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

}
