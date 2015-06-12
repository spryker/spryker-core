<?php

namespace SprykerFeature\Zed\StoreDistributor\Business\Distributor;

use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerFeature\Zed\QueueDistributor\Persistence\QueueDistributorQueryContainerInterface;

class QueueDistributor
{

    /**
     * @var QueueDistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var ItemDistributorInterface
     */
    protected $typeDistributor;

    /**
     * @param QueueDistributorQueryContainerInterface $queryContainer
     * @param ItemDistributorInterface $typeDistributor
     */
    public function __construct(
        QueueDistributorQueryContainerInterface $queryContainer,
        ItemDistributorInterface $typeDistributor
    ) {
        $this->queryContainer = $queryContainer;
        $this->typeDistributor = $typeDistributor;
    }

    /**
     * @param MessengerInterface $messenger
     */
    public function distributeData(MessengerInterface $messenger)
    {
        $typeKeys = $this->queryContainer->queryTypeKeys()->find();

        foreach ($typeKeys as $typeKey) {
            $this->typeDistributor->distributeByType($typeKey, $messenger);
        }
    }
}
