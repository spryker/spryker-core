<?php

namespace SprykerFeature\Zed\QueueDistributor\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueTypeTableMap;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouchQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTypeQuery;

class QueueDistributorQueryContainer extends AbstractQueryContainer implements
    QueueDistributorQueryContainerInterface
{
    /**
     * @return ModelCriteria
     */
    public function queryTypeKeys()
    {
        $query = SpyQueueTypeQuery::create()
            ->addSelectColumn(SpyQueueTypeTableMap::COL_KEY)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

    /**
     * @param $typeKey
     *
     * @return SpyQueueTypeQuery
     */
    public function queryTypeByKey($typeKey)
    {
        $query = SpyQueueTypeQuery::create()
            ->filterByKey($typeKey)
        ;

        return $query;
    }

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @return SpyQueueTouchQuery
     * @throws PropelException
     */
    public function queryTouchedItemsByType($typeKey, $timestamp)
    {
        return SpyQueueTouchQuery::create()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->filterByTouched(['min' => $timestamp])
            ->useSpyQueueTypeQuery()
            ->filterByKey($typeKey)
            ->endUse()
        ;
    }
}
