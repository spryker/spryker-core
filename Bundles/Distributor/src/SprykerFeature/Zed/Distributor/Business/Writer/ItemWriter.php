<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Distributor\Business\Exception\ItemTypeDoesNotExistException;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;
use Orm\Zed\Distributor\Persistence\SpyDistributorItem;

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
     * @throws ItemTypeDoesNotExistException
     * @throws PropelException
     */
    public function touchItem($itemType, $idItem)
    {
        $item = $this->queryContainer
            ->queryItemByTypeAndId($itemType, $idItem)
            ->findOne()
        ;

        $foreignKeyColumn = $this->getForeignKeyByType($itemType);

        if (!$item) {
            $this->createItem($itemType, $idItem, $foreignKeyColumn);
        } else {
            $item->setTouched(new \DateTime());
            $item->save();
        }
    }

    /**
     * @param string $itemType
     *
     * @return string
     */
    protected function getForeignKeyByType($itemType)
    {
        return 'fk_' . $itemType;
    }

    /**
     * @param string $itemType
     * @param int $idItem
     * @param string $foreignKeyColumn
     *
     * @throws PropelException
     * @throws ItemTypeDoesNotExistException
     */
    protected function createItem($itemType, $idItem, $foreignKeyColumn)
    {
        $itemType = $this->queryContainer->queryTypeByKey($itemType)->findOne();
        if (!$itemType) {
            throw new ItemTypeDoesNotExistException();
        }

        $item = new SpyDistributorItem();

        $item->setTouched(new \DateTime());
        $item->setByName($foreignKeyColumn, $idItem);
        $item->setFkItemType($itemType->getIdDistributorItemType());
        $item->save();

        $item->getIdDistributorItem();
    }

}
