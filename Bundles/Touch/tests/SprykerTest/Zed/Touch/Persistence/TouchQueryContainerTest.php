<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Persistence;

use Codeception\Test\Unit;
use DateTime;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouch;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Touch
 * @group Persistence
 * @group TouchQueryContainerTest
 * Add your own group annotations below this line
 */
class TouchQueryContainerTest extends Unit
{
    public const ITEM_TYPE = 'test.item';
    public const ITEM_ID_1 = 1;
    public const ITEM_ID_2 = 2;
    public const ITEM_ID_3 = 3;
    public const ITEM_ID_4 = 4;
    public const ITEM_ID_5 = 5;
    public const ITEM_ID_6 = 6;

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
