<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchStorageTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerEngine\Zed\Propel\Business\Formatter\PropelArraySetFormatter;

class TouchQueryContainer extends AbstractQueryContainer implements TouchQueryContainerInterface
{
    const TOUCH_ENTRY_QUERY_KEY = 'search touch entry';
    const TOUCH_ENTRIES_QUERY_KEY = 'search touch entries';
    const TOUCH_EXPORTER_ID = 'exporter_touch_id';

    /**
     * @param string $itemType
     *
     * @return SpyTouchQuery
     */
    public function queryTouchListByItemType($itemType)
    {
        $query = SpyTouchQuery::create();
        $query->filterByItemType($itemType);

        return $query;
    }

    /**
     * @param string $itemType
     * @param string $itemId
     *
     * @return SpyTouchQuery
     */
    public function queryTouchEntry($itemType, $itemId)
    {
        $query = SpyTouchQuery::create();
        $query
            ->setQueryKey(self::TOUCH_ENTRY_QUERY_KEY)
            ->filterByItemType($itemType)
            ->filterByItemId($itemId)
        ;
        
        return $query;
    }

    /**
     * @param string $itemType
     * @param LocaleTransfer $locale
     * @param \DateTime $lastTouchedAt
     * 
     * @return SpyTouchQuery
     * 
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function createBasicExportableQuery($itemType, LocaleTransfer $locale, \DateTime $lastTouchedAt)
    {
        $query = SpyTouchQuery::create();
        $query
            ->filterByItemType($itemType)
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->filterByTouched(['min' => $lastTouchedAt])
            ->withColumn(SpyTouchTableMap::COL_ID_TOUCH, self::TOUCH_EXPORTER_ID)
        ;

        return $query;
    }

    /**
     * @param string $itemType
     * @param LocaleTransfer $locale
     * @param \DateTime $lastTouchedAt
     
     * @return SpyTouchQuery
     * 
     * @throws \Propel\Runtime\Exception\PropelException
     */
/*    public function createBasicExportableQueryForDeletion($itemType, LocaleTransfer $locale, \DateTime $lastTouchedAt)
    {
        $query = SpyTouchQuery::create();
        $query
            ->filterByItemType($itemType)
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->filterByTouched(['min' => $lastTouchedAt])
            ->addJoin(
                SpyTouchTableMap::COL_ID_TOUCH,
                SpyTouchStorageTableMap::COL_FK_TOUCH,
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                SpyTouchStorageTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::LEFT_JOIN
            )
            ->addAnd(
                SpyTouchStorageTableMap::COL_FK_LOCALE,
                $locale->getIdLocale(),
                Criteria::EQUAL
            )
            ->withColumn(
                SpyTouchTableMap::COL_ITEM_ID,
                'node_id'
            )
            ->withColumn(
                SpyTouchStorageTableMap::COL_ID_TOUCH_STORAGE,
                'storage_id'
            )
            ->withColumn(
                SpyTouchStorageTableMap::COL_KEY,
                'storage_key'
            )
            ->withColumn(
                SpyTouchStorageTableMap::COL_FK_LOCALE,
                'storage_locale'
            )
            ->withColumn(
                SpyTouchTableMap::COL_ID_TOUCH,
                'touch_id'
            )
        ;

        return $query;
    }*/

    /**
     * @return SpyTouchQuery
     */
    public function queryExportTypes()
    {
        $query = SpyTouchQuery::create();
        $query
            ->addSelectColumn(SpyTouchTableMap::COL_ITEM_TYPE)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

    /**
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return SpyTouchQuery
     */
    public function queryTouchEntries($itemType, $itemEvent, array $itemIds)
    {
        $query = SpyTouchQuery::create()
            ->setQueryKey(self::TOUCH_ENTRIES_QUERY_KEY)
            ->filterByItemType($itemType)
            ->filterByItemEvent($itemEvent)
            ->filterByItemId($itemIds);

        return $query;
    }

}
