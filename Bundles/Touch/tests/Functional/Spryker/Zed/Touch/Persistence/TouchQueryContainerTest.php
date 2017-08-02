<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Touch\Persistence;

use Codeception\Test\Unit;
use DateTime;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouch;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Touch
 * @group Persistence
 * @group TouchQueryContainerTest
 */
class TouchQueryContainerTest extends Unit
{

    const ITEM_TYPE = 'test.item';
    const ITEM_ID_1 = 1;
    const ITEM_ID_2 = 2;
    const ITEM_ID_3 = 3;
    const ITEM_ID_4 = 4;
    const ITEM_ID_5 = 5;
    const ITEM_ID_6 = 6;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->createTouchEntity(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, self::ITEM_ID_1);
        $this->createTouchEntity(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, self::ITEM_ID_2);

        $this->createTouchEntity(SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, self::ITEM_ID_3);
        $this->createTouchEntity(SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, self::ITEM_ID_4);

        $this->createTouchEntity(SpyTouchTableMap::COL_ITEM_EVENT_DELETED, self::ITEM_ID_5);
        $this->createTouchEntity(SpyTouchTableMap::COL_ITEM_EVENT_DELETED, self::ITEM_ID_6);
    }

    /**
     * @dataProvider queryTouchEntriesDataProvider
     *
     * @deprecated This test can be removed when `TouchQueryContainerInterface::queryTouchEntries()` is removed
     *
     * @param string $itemEvent
     * @param array $itemsIds
     * @param int $expectedCount
     *
     * @return void
     */
    public function testQueryTouchEntries($itemEvent, array $itemsIds, $expectedCount)
    {
        $touchQueryContainer = new TouchQueryContainer();
        $touchQuery = $touchQueryContainer->queryTouchEntries(self::ITEM_TYPE, $itemEvent, $itemsIds);

        $this->assertCount($expectedCount, $touchQuery);
    }

    /**
     * @return array
     */
    public function queryTouchEntriesDataProvider()
    {
        return [
            [SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, [self::ITEM_ID_1, self::ITEM_ID_2], 2],
            [SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, [self::ITEM_ID_1, self::ITEM_ID_3], 1],
            [SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, [self::ITEM_ID_3, self::ITEM_ID_4], 2],
            [SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, [self::ITEM_ID_3, self::ITEM_ID_5], 1],
            [SpyTouchTableMap::COL_ITEM_EVENT_DELETED, [self::ITEM_ID_5, self::ITEM_ID_6], 2],
            [SpyTouchTableMap::COL_ITEM_EVENT_DELETED, [self::ITEM_ID_5, self::ITEM_ID_1], 1],
        ];
    }

    /**
     * @deprecated This test can be removed when `TouchQueryContainerInterface::queryTouchEntries()` is removed
     *
     * @return void
     */
    public function testQueryTouchEntriesThrowsException()
    {
        $touchQueryContainer = new TouchQueryContainer();
        $touchQueryContainer->queryTouchEntries(self::ITEM_TYPE, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, [self::ITEM_ID_1, self::ITEM_ID_2])->find();

        $this->expectException(PropelException::class);
        $touchQueryContainer->queryTouchEntries(self::ITEM_TYPE, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, [self::ITEM_ID_1])->find();
    }

    /**
     * @dataProvider queryTouchEntriesByItemTypeAndItemIdsDataProvider
     *
     * @param array $itemsIds
     * @param int $expectedCount
     *
     * @return void
     */
    public function testQueryTouchEntriesByItemTypeAndItemIds(array $itemsIds, $expectedCount)
    {
        $touchQueryContainer = new TouchQueryContainer();
        $touchQuery = $touchQueryContainer->queryTouchEntriesByItemTypeAndItemIds(self::ITEM_TYPE, $itemsIds);

        $this->assertCount($expectedCount, $touchQuery);
    }

    /**
     * @return array
     */
    public function queryTouchEntriesByItemTypeAndItemIdsDataProvider()
    {
        return [
            [[self::ITEM_ID_1], 1],
            [[self::ITEM_ID_1, self::ITEM_ID_2], 2],
            [[self::ITEM_ID_3], 1],
            [[self::ITEM_ID_3, self::ITEM_ID_4], 2],
            [[self::ITEM_ID_5], 1],
            [[self::ITEM_ID_5, self::ITEM_ID_6], 2],
        ];
    }

    /**
     * @param string $itemEvent
     * @param int $itemId
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouch
     */
    protected function createTouchEntity($itemEvent, $itemId)
    {
        $touchEntity = new SpyTouch();
        $touchEntity->setItemEvent($itemEvent)
            ->setItemId($itemId)
            ->setItemType(self::ITEM_TYPE)
            ->setTouched(new DateTime());

        $touchEntity->save();

        return $touchEntity;
    }

}
