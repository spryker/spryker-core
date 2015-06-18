<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Provider;

use SprykerFeature\Zed\QueueDistributor\Business\Builder\QueueNameBuilderInterface;
use SprykerFeature\Zed\QueueDistributor\Persistence\QueueDistributorQueryContainerInterface;

class ItemQueueProvider implements ItemQueueProviderInterface
{
    /**
     * @var QueueDistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var QueueNameBuilderInterface
     */
    protected $queueNameBuilder;

    /**
     * @param QueueDistributorQueryContainerInterface $queryContainer
     * @param QueueNameBuilderInterface $queueKeyBuilder
     */
    public function __construct(
        QueueDistributorQueryContainerInterface $queryContainer,
        QueueNameBuilderInterface $queueKeyBuilder
    ) {
        $this->queryContainer = $queryContainer;
        $this->queueNameBuilder = $queueKeyBuilder;
    }

    /**
     * @param string $itemType
     *
     * @return array
     */
    public function getAllQueueForType($itemType)
    {
        $receivers = $this->queryContainer->queryReceivers()->find();
        $queues = [];

        foreach ($receivers as $receiver) {
            $queues[] = $this->queueNameBuilder->createQueueName($itemType, $receiver);
        }

        return $queues;
    }
}
