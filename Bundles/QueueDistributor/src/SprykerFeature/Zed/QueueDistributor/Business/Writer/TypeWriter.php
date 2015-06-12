<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueType;
use SprykerFeature\Zed\QueueDistributor\Persistence\QueueDistributorQueryContainerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class TypeWriter implements TypeWriterInterface
{
    /**
     * @var QueueDistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param QueueDistributorQueryContainerInterface $queryContainer
     */
    public function __construct(QueueDistributorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param $typeKey
     *
     * @return int
     * @throws PropelException
     */
    public function create($typeKey)
    {
        $distribution = new SpyQueueType();
        $distribution->setKey($typeKey);
        $distribution->save();

        return $distribution->getIdQueueType();
    }

    /**
     * @param $typeKey
     * @param $timestamp
     *
     * @throws PropelException
     * @return int
     */
    public function update($typeKey, $timestamp)
    {
        $distribution = $this->queryContainer
            ->queryTypeByKey($typeKey)
            ->findOne()
        ;

        if (empty($distribution)) {
            throw new Exception;
        }
        $distribution->getLastDistribution($timestamp);
        $distribution->save();

        return $distribution->getIdQueueType();
    }
}
