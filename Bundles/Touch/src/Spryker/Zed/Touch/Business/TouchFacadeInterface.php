<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business;

interface TouchFacadeInterface
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
    public function touchActive($itemType, $idItem, $keyChange = false);

    /**
     * @api
     *
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchInactive($itemType, $idItem);

    /**
     * @api
     *
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idItem);

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
    public function bulkTouchActive($itemType, array $itemIds = []);

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
    public function bulkTouchSetActive($itemType, array $itemIds);

    /**
     * Specification
     *  - set `touched` to current date of items with given `$itemType`, `$itemId` where `item_event` is `inactive`
     *
     * @api
     *
     * @deprecated Use bulkTouchSetInActive instead
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouchInactive($itemType, array $itemIds = []);

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
    public function bulkTouchSetInActive($itemType, array $itemIds);

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
    public function bulkTouchDeleted($itemType, array $itemIds = []);

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
    public function bulkTouchSetDeleted($itemType, array $itemIds);

    /**
     * @api
     *
     * @param string $itemType
     *
     * @return \Generated\Shared\Transfer\TouchTransfer[]
     */
    public function getItemsByType($itemType);

    /**
     * Specification:
     * - Removes all the rows from the touch table(s)
     *   which are marked as deleted (item_event = SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
     *
     * @api
     *
     * @return int
     */
    public function removeTouchEntriesMarkedAsDeleted();
}
