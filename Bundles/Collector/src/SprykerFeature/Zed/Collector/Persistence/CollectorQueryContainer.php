<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Persistence;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;

class CollectorQueryContainer extends AbstractQueryContainer
{

    /**
     * @param string $type
     * @param \DateTime $lastExportedAt
     *
     * @return SpyTouchQuery
     */
    public function createBasicExportableQuery($type, \DateTime $lastExportedAt)
    {
        return SpyTouchQuery::create()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->filterByTouched(['min' => $lastExportedAt])
            ->filterByItemType($type);
    }

    /**
     * @throws PropelException
     *
     * @return SpyTouchQuery
     */
    public function queryExportTypes()
    {
        $query = SpyTouchQuery::create()
            ->addSelectColumn(SpyTouchTableMap::COL_ITEM_TYPE)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

}
