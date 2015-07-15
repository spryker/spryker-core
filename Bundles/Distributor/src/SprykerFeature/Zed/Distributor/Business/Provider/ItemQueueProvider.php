<?php

namespace SprykerFeature\Zed\Distributor\Business\Provider;

use SprykerFeature\Zed\Distributor\Business\Builder\QueueNameBuilderInterface;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;

class ItemQueueProvider implements ItemQueueProviderInterface
{

    /**
     * @var DistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var QueueNameBuilderInterface
     */
    protected $queueNameBuilder;

    /**
     * @param DistributorQueryContainerInterface $queryContainer
     * @param QueueNameBuilderInterface $queueKeyBuilder
     */
    public function __construct(
        DistributorQueryContainerInterface $queryContainer,
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
    public function getAllQueuesForType($itemType)
    {
        $receivers = $this->queryContainer->queryReceivers()->find();
        $queues = [];

        foreach ($receivers as $receiver) {
            $queues[] = $this->queueNameBuilder->createQueueName($receiver, $itemType);
        }

        return $queues;
    }

}
