<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;

class ItemWriter implements ItemWriterInterface
{

    /**
     * @var DistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param DistributorQueryContainerInterface $queryContainer
     */
    public function __construct(DistributorQueryContainerInterface $queryContainer)
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

        return $item->getIdDistributorItem();
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
