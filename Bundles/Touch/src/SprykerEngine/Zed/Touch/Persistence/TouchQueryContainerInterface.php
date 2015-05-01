<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Zed\Touch\Persistence;

use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;

interface TouchQueryContainerInterface
{
    /**
     * @param string $itemType
     *
     * @return SpyTouchQuery
     */
    public function queryTouchListByItemType($itemType);

    /**
     * @param string $itemType
     * @param string $itemId
     *
     * @return SpyTouchQuery
     */
    public function queryTouchEntry($itemType, $itemId);
}
