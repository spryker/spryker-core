<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business;

interface TouchFacadeInterface
{
    /**
     * Specification:
     * - Updates or inserts a 'touch active' record for the provided entity.
     * - Touches record with current date.
     * - Updates previous touch record as 'touch deleted' when key change is set as true.
     *
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
     * Specification:
     * - Updates or inserts a 'touch inactive' record for the provided entity.
     * - Touches record with current date.
     *
     * @api
     *
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchInactive($itemType, $idItem);

    /**
     * Specification:
     * - Updates or inserts a 'touch deleted' record for the provided entity.
     * - Touches record with current date.
     *
     * @api
     *
     * @param string $itemType
     * @param int $idItem
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idItem);

    /**
     * Specification:
     * - Sets provided records as 'touch active'.
     * - Touches changed records with current date.
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
     * Specification:
     * - Sets provided records as 'touch inactive'.
     * - Touches changed records with current date.
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
     * Specification:
     * - Sets provided records as 'touch deleted'.
     * - Touches changed records with current date.
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
     * Specification:
     * - Retrieves touch entities by item type as a list of Touch transfer objects.
     *
     * @api
     *
     * @param string $itemType
     *
     * @return \Generated\Shared\Transfer\TouchTransfer[]
     */
    public function getItemsByType($itemType);

    /**
     * @deprecated Use TouchFacadeInterface::cleanTouchEntitiesForDeletedItemEvent() instead.
     *
     * Specification:
     * - Removes all 'touch deleted' records from touch table(s).
     *
     * @api
     *
     * @return int
     */
    public function removeTouchEntriesMarkedAsDeleted();

    /**
     * Specification:
     * - Removes all touch entities which have 'item_event=deleted'.
     * - Returns deleted touch entities count.
     *
     * @api
     *
     * @return int
     */
    public function cleanTouchEntitiesForDeletedItemEvent(): int;
}
