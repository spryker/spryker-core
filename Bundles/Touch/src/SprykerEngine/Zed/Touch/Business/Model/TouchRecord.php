<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Business\Model;

use DateTime;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouch;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;

class TouchRecord implements TouchRecordInterface
{

    /**
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param TouchQueryContainerInterface $queryContainer
     * @param ConnectionInterface $connection
     */
    public function __construct(TouchQueryContainerInterface $queryContainer, ConnectionInterface $connection)
    {
        $this->touchQueryContainer = $queryContainer;
        $this->connection = $connection;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param int $idItem
     * @param bool $keyChange
     *
     * @throws \Exception
     * @throws PropelException
     *
     * @return bool
     */
    public function saveTouchRecord($itemType, $itemEvent, $idItem, $keyChange = false)
    {
        $this->connection->beginTransaction();

        if ($keyChange) {
            $this->insertKeyChangeRecord($itemType, $idItem);
        }

        if ($itemEvent === SpyTouchTableMap::COL_ITEM_EVENT_DELETED) {
            if (!$this->deleteKeyChangeActiveRecord($itemType, $idItem)) {
                $this->insertTouchRecord($itemType, $itemEvent, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
            }
        } else {
            $this->insertTouchRecord($itemType, $itemEvent, $idItem);
        }

        $this->connection->commit();

        return true;
    }

    /**
     * @param string $itemType
     * @param int $idItem
     * @param string $itemEvent
     * @param SpyTouch $touchEntity
     */
    protected function saveTouchEntity($itemType, $idItem, $itemEvent, SpyTouch $touchEntity)
    {
        $touchEntity->setItemType($itemType)
            ->setItemEvent($itemEvent)
            ->setItemId($idItem)
            ->setTouched(new DateTime())
        ;
        $touchEntity->save();
    }

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    protected function deleteKeyChangeActiveRecord($itemType, $idItem)
    {
        $touchDeletedEntity = $this->touchQueryContainer
            ->queryUpdateTouchEntry($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->findOne()
        ;

        if (null === $touchDeletedEntity) {
            return false;
        }

        $touchActiveEntity = $this->touchQueryContainer
            ->queryUpdateTouchEntry($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->findOne()
        ;

        if (null !== $touchActiveEntity) {
            $touchActiveEntity->delete();
        }

        return true;
    }

    /**
     * @param string $itemType
     * @param string $idItem
     */
    protected function insertKeyChangeRecord($itemType, $idItem)
    {
        $touchOldEntity = $this->touchQueryContainer->queryUpdateTouchEntry($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->findOne()
        ;
        if (null === $touchOldEntity) {
            return;
        }

        $touchDeletedEntity = $this->touchQueryContainer->queryUpdateTouchEntry($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->findOne()
        ;
        if (null === $touchDeletedEntity) {
            $this->saveTouchEntity($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $touchOldEntity);
        }
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param string $idItem
     * @param string $type;
     */
    protected function insertTouchRecord($itemType, $itemEvent, $idItem, $type = null)
    {
        if (null === $type) {
            $type = $itemEvent;
        }
        $touchEntity = $this->touchQueryContainer->queryUpdateTouchEntry($itemType, $idItem, $type)
            ->findOneOrCreate()
        ;
        $this->saveTouchEntity($itemType, $idItem, $itemEvent, $touchEntity);
    }

}
