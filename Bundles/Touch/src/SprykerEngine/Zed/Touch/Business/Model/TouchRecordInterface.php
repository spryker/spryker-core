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
     * @return bool
     * @throws \Exception
     * @throws PropelException
     */
    public function saveTouchRecord($itemType, $itemEvent, $idItem);
}
