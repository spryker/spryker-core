<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Business\Model;

use Propel\Runtime\Exception\PropelException;

interface TouchRecordInterface
{

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param int $idItem
     *
     * @throws \Exception
     * @throws PropelException
     *
     * @return bool
     */
    public function saveTouchRecord($itemType, $itemEvent, $idItem);

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkUpdateTouchRecords($itemType, $itemEvent, array $itemIds = []);

}
