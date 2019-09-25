<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Persistence;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

/**
 * @method \Spryker\Zed\Touch\Persistence\TouchPersistenceFactory getFactory()
 */
class TouchQueryContainer extends AbstractQueryContainer implements TouchQueryContainerInterface
{
    public const TOUCH_ENTRY_QUERY_KEY = 'search touch entry';
    public const TOUCH_ENTRIES_QUERY_KEY = 'search touch entries';

    /**
     * @api
     *
     * @param string $itemType
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchListByItemType($itemType)
    {
        $query = $this->getFactory()->createTouchQuery();
        $query->filterByItemType($itemType);

        return $query;
    }

    /**
     * @api
     *
     * @param string $itemType
     * @param string $itemId
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchEntry($itemType, $itemId)
    {
        $query = $this->getFactory()->createTouchQuery();
        $query
            ->setQueryKey(self::TOUCH_ENTRY_QUERY_KEY)
            ->filterByItemType($itemType)
            ->filterByItemId($itemId);

        return $query;
    }

    /**
     * @api
     *
     * @param string $itemType
     * @param int $itemId
     * @param string|null $itemEvent
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryUpdateTouchEntry($itemType, $itemId, $itemEvent = null)
    {
        $query = $this->getFactory()->createTouchQuery();
        $query
            ->filterByItemType($itemType)
            ->filterByItemId($itemId);

        if ($itemEvent !== null) {
            $query->filterByItemEvent($itemEvent);
        }

        return $query;
    }

    /**
     * @api
     *
     * @param string $itemType
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \DateTime $lastTouchedAt
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function createBasicExportableQuery($itemType, LocaleTransfer $locale, DateTime $lastTouchedAt)
    {
        $query = $this->getFactory()->createTouchQuery();
        $query
            ->filterByItemType($itemType)
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->filterByTouched(['min' => $lastTouchedAt], Criteria::GREATER_EQUAL);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryExportTypes()
    {
        $query = $this->getFactory()->createTouchQuery();
        $query
            ->addSelectColumn(SpyTouchTableMap::COL_ITEM_TYPE)
            ->setDistinct()
            ->orderBy(SpyTouchTableMap::COL_ITEM_TYPE)
            ->setFormatter(new PropelArraySetFormatter());

        return $query;
    }

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
    public function queryTouchEntriesByItemTypeAndItemIds($itemType, array $itemIds)
    {
        $query = $this->getFactory()->createTouchQuery()
            ->filterByItemType($itemType)
            ->filterByItemId($itemIds, Criteria::IN);

        return $query;
    }

    /**
     * @api
     *
     * @param string $itemType
     * @param int $idStore
     * @param int|null $idLocale
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchDeleteStorageAndSearch($itemType, $idStore, $idLocale = null)
    {
        $query = $this->getFactory()
            ->createTouchQuery()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->filterByItemType($itemType)
            ->leftJoinTouchSearch('search')
            ->leftJoinTouchStorage('storage')
            ->addJoinCondition('search', sprintf('search.fk_store = %d', $idStore))
            ->addJoinCondition('storage', sprintf('storage.fk_store = %d', $idStore));

        if ($idLocale) {
            $query
                ->addJoinCondition('search', sprintf('search.fk_locale = %d', $idLocale))
                ->addJoinCondition('storage', sprintf('storage.fk_locale = %d', $idLocale));
        }

        return $query;
    }

    /**
     * @api
     *
     * @param string $itemEvent
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchListByItemEvent($itemEvent)
    {
        $query = $this->getFactory()->createTouchQuery();
        $query->filterByItemEvent($itemEvent);

        return $query;
    }

    /**
     * @api
     *
     * @param array $touchIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchSearchQuery
     */
    public function queryTouchSearchByTouchIds($touchIds)
    {
        $query = $this->getFactory()->createTouchSearchQuery();
        if (is_array($touchIds)) {
            $query->filterByFkTouch($touchIds, Criteria::IN);

            return $query;
        }

        $query->filterByFkTouch($touchIds, Criteria::EQUAL);

        return $query;
    }

    /**
     * @api
     *
     * @param array $touchIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchStorageQuery
     */
    public function queryTouchStorageByTouchIds($touchIds)
    {
        $query = $this->getFactory()->createTouchStorageQuery();
        if (is_array($touchIds)) {
            $query->filterByFkTouch($touchIds, Criteria::IN);

            return $query;
        }

        $query->filterByFkTouch($touchIds, Criteria::EQUAL);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchEntriesByItemTypeAndItemEventAndItemIds(string $itemType, string $itemEvent, array $itemIds): SpyTouchQuery
    {
        $query = $this->getFactory()->createTouchQuery()
            ->filterByItemType($itemType)
            ->filterByItemEvent($itemEvent)
            ->filterByItemId($itemIds, Criteria::IN);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchEntriesByItemTypeAndItemIdsAllowableToUpdateWithItemEvent(string $itemType, string $itemEvent, array $itemIds): SpyTouchQuery
    {
        $query = $this->queryTouchEntriesByItemTypeAndItemIds($itemType, $itemIds)
            ->condition('itemEventFilterCondition', SpyTouchTableMap::COL_ITEM_EVENT . ' = ?', $itemEvent)
            ->condition('isUniqueTouchItemCondition', $this->getTouchCounterSubQuery())
            ->combine(['itemEventFilterCondition', 'isUniqueTouchItemCondition'], Criteria::LOGICAL_OR);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $touchIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function queryTouchEntriesByTouchIds(array $touchIds): SpyTouchQuery
    {
        $query = $this->getFactory()->createTouchQuery()
            ->filterByIdTouch_In($touchIds);

        return $query;
    }

    /**
     * @return string
     */
    protected function getTouchCounterSubQuery(): string
    {
        $subQuery = '(SELECT COUNT(*) FROM spy_touch as alias WHERE alias.item_id = spy_touch.item_id AND alias.item_type = spy_touch.item_type) = 1';

        return $subQuery;
    }
}
