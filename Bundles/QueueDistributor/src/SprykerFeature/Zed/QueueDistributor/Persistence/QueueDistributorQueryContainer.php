<?php

namespace SprykerFeature\Zed\QueueDistributor\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueItemTypeTableMap;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueReceiverTableMap;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemTypeQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueItemTableMap;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueReceiverQuery;

class QueueDistributorQueryContainer extends AbstractQueryContainer implements
    QueueDistributorQueryContainerInterface
{
    /**
     * @return ModelCriteria
     */
    public function queryItemTypes()
    {
        $query = SpyQueueItemTypeQuery::create()
            ->addSelectColumn(SpyQueueItemTypeTableMap::COL_KEY)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

    /**
     * @param string $typeKey
     *
     * @return $this|SpyQueueItemTypeQuery
     */
    public function queryTypeByKey($typeKey)
    {
        $query = SpyQueueItemTypeQuery::create()
            ->filterByKey($typeKey)
        ;

        return $query;
    }

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @throws PropelException
     * @return SpyQueueItemQuery
     */
    public function queryTouchedItemsByTypeKey($typeKey, $timestamp)
    {
        return SpyQueueItemQuery::create()
            ->filterByItemEvent(SpyQueueItemTableMap::COL_ITEM_EVENT_ACTIVE)
            ->filterByTouched(['min' => $timestamp])
            ->useSpyQueueItemTypeQuery()
            ->filterByKey($typeKey)
            ->endUse()
        ;
    }

    /**
     * @return $this|ModelCriteria
     */
    public function queryReceivers()
    {
        $query = SpyQueueReceiverQuery::create()
            ->addSelectColumn(SpyQueueReceiverTableMap::COL_KEY)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

    /**
     * @param string $itemType
     * @param string $idItem
     *
     * @return SpyQueueItemQuery
     */
    public function queryItemByTypeAndId($itemType, $idItem)
    {
        $foreignKeyColumn = $this->getForeignKeyColumnByType($itemType);

        $query = SpyQueueItemQuery::create()
            ->filterBy($foreignKeyColumn, $idItem)
            ->useSpyQueueItemTypeQuery()
            ->filterByKey($itemType)
            ->endUse()
        ;

        return $query;
    }

    /**
     * @param $itemType
     *
     * @return string
     */
    protected function getForeignKeyColumnByType($itemType)
    {
        return 'fk_' . $itemType;
    }
}
