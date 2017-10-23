<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Persistence;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface TouchQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param string $itemType
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchListByItemType($itemType);

    /**
     * @api
     *
     * @param string $itemType
     * @param string $itemId
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchEntry($itemType, $itemId);

    /**
     * @api
     *
     * @param string $itemType
     * @param string $itemId
     * @param string|null $itemEvent
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryUpdateTouchEntry($itemType, $itemId, $itemEvent = null);

    /**
     * Specification:
     *  - return all items with given `$itemType`, `$itemEvent` and `$itemId`
     *
     * e.g. `queryTouchEntries('cms.page', 'active', [1, 2])->find()` will return all
     * 'active' 'cms.page' items with id 1 and/or 2
     *
     * Attention: The method makes wrong use of query cache!
     * When this method is used more then once with different itemId count it will fail.
     *
     * 1. `queryTouchEntries('cms.page', 'active', [1])->find()`
     * SQL: SELECT "spy_touch"."item_id" AS "spy_touch.item_id" FROM "spy_touch" WHERE "spy_touch"."item_type"=:p1 AND "spy_touch"."item_event"=:p2 AND "spy_touch"."item_id" IN (:p3)
     *
     * 2. `queryTouchEntries('cms.page', 'active', [1, 2])->find()`
     * SQL: SELECT "spy_touch"."item_id" AS "spy_touch.item_id" FROM "spy_touch" WHERE "spy_touch"."item_type"=:p1 AND "spy_touch"."item_event"=:p2 AND "spy_touch"."item_id" IN (:p3,:p4)
     *
     * For the 2. the first generated (cached) SQL will be used but the IN part now has one more entry and will throw an exception
     *
     * @api
     *
     * @deprecated Use `queryTouchEntriesByItemTypeAndItemIds()` instead
     *
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchEntries($itemType, $itemEvent, array $itemIds);

    /**
     * Specification:
     *  - return all items with given `$itemType` and `$itemId` whether they are active, inactive or deleted
     *
     * @api
     *
     * @param string $itemType
     * @param array $itemIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchEntriesByItemTypeAndItemIds($itemType, array $itemIds);

    /**
     * @api
     *
     * @param string $itemType
     * @param int|null $idLocale
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchDeleteStorageAndSearch($itemType, $idLocale = null);

    /**
     * @api
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryExportTypes();

    /**
     * @api
     *
     * @param string $itemType
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \DateTime $lastTouchedAt
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function createBasicExportableQuery($itemType, LocaleTransfer $locale, DateTime $lastTouchedAt);

    /**
     * @api
     *
     * @param string $itemEvent
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchListByItemEvent($itemEvent);

    /**
     * @api
     *
     * @param array $touchIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchSearchQuery
     */
    public function queryTouchSearchByTouchIds($touchIds);

    /**
     * @api
     *
     * @param array $touchIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchStorageQuery
     */
    public function queryTouchStorageByTouchIds($touchIds);
}
