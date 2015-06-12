<?php

namespace SprykerFeature\Zed\QueueDistributor\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouchQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTypeQuery;

interface QueueDistributorQueryContainerInterface
{
    /**
     * @return ModelCriteria
     */
    public function queryTypeKeys();

    /**
     * @param $typeKey
     *
     * @return SpyQueueTypeQuery
     */
    public function queryTypeByKey($typeKey);

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @return SpyQueueTouchQuery
     * @throws PropelException
     */
    public function queryTouchedItemsByType($typeKey, $timestamp);
}
