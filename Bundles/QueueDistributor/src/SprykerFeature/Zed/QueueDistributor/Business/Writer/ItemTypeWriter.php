<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType;
use SprykerFeature\Zed\QueueDistributor\Persistence\QueueDistributorQueryContainerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class ItemTypeWriter implements ItemTypeWriterInterface
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
     * @param $queueName
     *
     * @return int
     */
    public function create($queueName)
    {
        $distribution = new SpyQueueItemType();
        $distribution->setKey($queueName);
        $distribution->save();

        return $distribution->getIdQueueItemType();
    }

    /**
     * @param string $typeKey
     * @param string $timestamp
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

        return $distribution->getIdQueueItemType();
    }
}
