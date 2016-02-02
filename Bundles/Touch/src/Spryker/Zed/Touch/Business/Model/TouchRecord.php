<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Touch\Business\Model;

use DateTime;
use Propel\Runtime\Connection\ConnectionInterface;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouch;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

class TouchRecord implements TouchRecordInterface
{

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
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
     * @throws \Propel\Runtime\Exception\PropelException
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
     * @param \Orm\Zed\Touch\Persistence\SpyTouch $touchEntity
     *
     * @return void
     */
    protected function saveTouchEntity($itemType, $idItem, $itemEvent, SpyTouch $touchEntity)
    {
        $touchEntity->setItemType($itemType)
            ->setItemEvent($itemEvent)
            ->setItemId($idItem)
            ->setTouched(new DateTime());
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
            ->findOne();

        if ($touchDeletedEntity === null) {
            return false;
        }

        $touchActiveEntity = $this->touchQueryContainer
            ->queryUpdateTouchEntry($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->findOne();

        if ($touchActiveEntity !== null) {
            $touchActiveEntity->delete();
        }

        return true;
    }

    /**
     * @param string $itemType
     * @param string $idItem
     *
     * @return void
     */
    protected function insertKeyChangeRecord($itemType, $idItem)
    {
        $touchOldEntity = $this->touchQueryContainer->queryUpdateTouchEntry($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->findOne();
        if ($touchOldEntity === null) {
            return;
        }

        $touchDeletedEntity = $this->touchQueryContainer->queryUpdateTouchEntry($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->findOne();
        if ($touchDeletedEntity === null) {
            $this->saveTouchEntity($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $touchOldEntity);
        }
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param string $idItem
     * @param string $type;
     *
     * @return void
     */
    protected function insertTouchRecord($itemType, $itemEvent, $idItem, $type = null)
    {
        if ($type === null) {
            $type = $itemEvent;
        }
        $touchEntity = $this->touchQueryContainer->queryUpdateTouchEntry($itemType, $idItem, $type)
            ->findOneOrCreate();
        $this->saveTouchEntity($itemType, $idItem, $itemEvent, $touchEntity);
    }

}
