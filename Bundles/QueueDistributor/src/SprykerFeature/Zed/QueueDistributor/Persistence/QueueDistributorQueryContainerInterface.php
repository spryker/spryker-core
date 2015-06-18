<?php

namespace SprykerFeature\Zed\QueueDistributor\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemTypeQuery;

interface QueueDistributorQueryContainerInterface
{
    /**
     * @return ModelCriteria
     */
    public function queryItemTypes();

    /**
     * @param string $typeKey
     *
     * @return $this|SpyQueueItemTypeQuery
     */
    public function queryTypeByKey($typeKey);

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @throws PropelException
     * @return SpyQueueItemQuery
     */
    public function queryTouchedItemsByTypeKey($typeKey, $timestamp);

    /**
     * @return $this|ModelCriteria
     */
    public function queryReceivers();

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return SpyQueueItemQuery
     */
    public function queryItemByTypeAndId($itemType, $idItem);
}
