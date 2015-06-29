<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;
use SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItem;

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
            ->findOne()
        ;

        $foreignKeyColumn = $this->getForeignKeyByType($itemType);

        if (!$item) {
            return $this->createItem($itemType, $idItem, $foreignKeyColumn);
        }

        $item->setTouched(new \DateTime());
        $item->save();

        return $item->getIdDistributorItem();
    }

    /**
     * @param string $itemType
     *
     * @return string
     */
    public function getForeignKeyByType($itemType)
    {
        return 'fk_' . $itemType;
    }

    /**
     * @param $itemType
     *
     * @return bool
     */
    public function touchAllItemsByType($itemType)
    {
    }

    /**
     * @param string $itemType
     * @param int $idItem
     * @param string $foreignKeyColumn
     *
     * @throws PropelException
     * @return int
     */
    protected function createItem($itemType, $idItem, $foreignKeyColumn)
    {
        $itemType = $this->queryContainer->queryTypeByKey($itemType)->findOne();
        if (!$itemType) {

        }

        $item = new SpyDistributorItem();

        $item->setTouched(new \DateTime());
        $item->setByName($foreignKeyColumn, $idItem);
        $item->setFkItemType($itemType->getIdDistributorItemType());
        $item->save();

        return $item->getIdDistributorItem();
    }
}
