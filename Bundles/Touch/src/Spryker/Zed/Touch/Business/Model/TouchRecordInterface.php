<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model;

interface TouchRecordInterface
{
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
    public function saveTouchRecord($itemType, $itemEvent, $idItem, $keyChange = false);

    /**
     * Removes all the rows from the touch table(s)
     * which are marked as deleted (item_event = 2)
     *
     * @api
     *
     * @return int
     */
    public function removeTouchEntriesMarkedAsDeleted();

    /**
     * @return int
     */
    public function cleanTouchEntitiesForDeletedItemEvent(): int;
}
