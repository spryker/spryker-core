<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Touch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;

/**
 * @method \Spryker\Zed\Touch\Business\TouchBusinessFactory getFactory()
 */
class TouchFacade extends AbstractFacade
{

    /**
     * @param string $itemType
     * @param int $idItem
     * @param bool $keyChange
     *
     * @return bool
     */
    public function touchActive($itemType, $idItem, $keyChange = false)
    {
        $touchRecordModel = $this->getFactory()->createTouchRecordModel();

        return $touchRecordModel->saveTouchRecord(
            $itemType,
            SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE,
            $idItem,
            $keyChange
        );
    }

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchInactive($itemType, $idItem)
    {
        $touchRecordModel = $this->getFactory()->createTouchRecordModel();

        return $touchRecordModel->saveTouchRecord(
            $itemType,
            SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE,
            $idItem
        );
    }

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idItem)
    {
        $touchRecordModel = $this->getFactory()->createTouchRecordModel();

        return $touchRecordModel->saveTouchRecord(
            $itemType,
            SpyTouchTableMap::COL_ITEM_EVENT_DELETED,
            $idItem
        );
    }

    /**
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchActive($itemType, array $itemIds = [])
    {
        $touchModel = $this->getFactory()->createTouchModel();

        return $touchModel->bulkUpdateTouchRecords($itemType, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $itemIds);
    }

    /**
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchInactive($itemType, array $itemIds = [])
    {
        $touchModel = $this->getFactory()->createTouchModel();

        return $touchModel->bulkUpdateTouchRecords($itemType, SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, $itemIds);
    }

    /**
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchDeleted($itemType, array $itemIds = [])
    {
        $touchModel = $this->getFactory()->createTouchModel();

        return $touchModel->bulkUpdateTouchRecords($itemType, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $itemIds);
    }

    /**
     * @param string $itemType
     *
     * @return \Generated\Shared\Transfer\TouchTransfer[]
     */
    public function getItemsByType($itemType)
    {
        $touchModel = $this->getFactory()->createTouchModel();

        return $touchModel->getItemsByType($itemType);
    }

}
