<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business;

use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Touch\Business\TouchBusinessFactory getFactory()
 */
class TouchFacade extends AbstractFacade implements TouchFacadeInterface
{
    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
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
     * Specification
     *  - set `touched` to current date of items with given `$itemType`, `$itemId` where `item_event` is `active`
     *
     * @api
     *
     * @deprecated Use bulkTouchSetActive instead
     *
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
     * Specification
     *  - set `touched` to current date and `item_event` to `active` where given `$itemType` and `$itemId` matches
     *
     * @api
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchSetActive($itemType, array $itemIds)
    {
        $touchModel = $this->getFactory()->createBulkTouchModel();

        return $touchModel->bulkTouch($itemType, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $itemIds);
    }

    /**
     * Specification
     *  - set `touched` to current date of items with given `$itemType`, `$itemId` where `item_event` is `inactive`
     *
     * @api
     *
     * @deprecated Use bulkTouchSetInactive instead
     *
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
     * Specification
     *  - set `touched` to current date and `item_event` to `inactive` where given `$itemType` and `$itemId` matches
     *
     * @api
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchSetInActive($itemType, array $itemIds)
    {
        $touchModel = $this->getFactory()->createBulkTouchModel();

        return $touchModel->bulkTouch($itemType, SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, $itemIds);
    }

    /**
     * Specification
     *  - set `touched` to current date of items with given `$itemType`, `$itemId` where `item_event` is `deleted`
     *
     * @api
     *
     * @deprecated Use bulkTouchSetDeleted instead
     *
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
     * Specification
     *  - set `touched` to current date and `item_event` to `deleted` where given `$itemType` and `$itemId` matches
     *
     * @api
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchSetDeleted($itemType, array $itemIds)
    {
        $touchModel = $this->getFactory()->createBulkTouchModel();

        return $touchModel->bulkTouch($itemType, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $itemIds);
    }

    /**
     * @api
     *
     * @param string $itemType
     *
     * @return \Generated\Shared\Transfer\TouchTransfer[]
     */
    public function getItemsByType($itemType)
    {
        $touchModel = $this->getFactory()->createTouchModel();

        return $touchModel->getItemsByType($itemType);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     */
    public function removeTouchEntriesMarkedAsDeleted()
    {
        $touchRecordModel = $this->getFactory()->createTouchRecordModel();
        return $touchRecordModel->removeTouchEntriesMarkedAsDeleted();
    }
}
