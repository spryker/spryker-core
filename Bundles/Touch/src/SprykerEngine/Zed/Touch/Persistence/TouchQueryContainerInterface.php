<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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

    /**
     * @param string $itemType
     * @param string $itemId
     * @param string $itemEvent
     *
     * @return SpyTouchQuery
     */
    public function queryUpdateTouchEntry($itemType, $itemId, $itemEvent);

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return SpyTouchQuery
     */
    public function queryTouchEntries($itemType, $itemEvent, array $itemIds);

}
