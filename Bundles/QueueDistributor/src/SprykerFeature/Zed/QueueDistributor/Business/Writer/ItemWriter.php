<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Writer;

use SprykerFeature\Zed\QueueDistributor\Persistence\QueueDistributorQueryContainerInterface;

class ItemWriter implements ItemWriterInterface
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
     * @param string $itemType
     * @param int $idItem
     *
     * @return int
     */
    public function touchItem($itemType, $idItem)
    {
        $item = $this->queryContainer
            ->queryItemByTypeAndId($itemType, $idItem)
            ->findOneOrCreate()
        ;
        $item->setTouched(new \DateTime());
        $item->save();

        return $item->getIdQueueItem();
    }

    /**
     * @param $itemType
     *
     * @return bool
     */
    public function touchAllItemsByType($itemType)
    {
    }
}
