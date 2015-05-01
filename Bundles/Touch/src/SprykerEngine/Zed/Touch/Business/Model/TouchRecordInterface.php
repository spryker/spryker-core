<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
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
