<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Touch\Business\TouchBusinessFactory;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Touch\TouchDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Touch
 * @group Business
 * @group Model
 * @group TouchRecordTest
 * Add your own group annotations below this line
 */
class TouchRecordTest extends Unit
{
    protected const TYPE_CATEGORY = 'category';

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Business\Model\TouchRecordInterface
     */
    protected $touchRecord;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $container = new Container();
        $dependencyProvider = new TouchDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $businessFactory = new TouchBusinessFactory();
        $businessFactory->setContainer($container);

        $this->touchQueryContainer = new TouchQueryContainer();
        $this->touchRecord = $businessFactory->createTouchRecordModel();
    }

    /**
     * @return void
     */
    public function testSaveTouchRecordKeepsOneRecordIfKeyChangeFalse(): void
    {
        // Assign
        $idItem = 100000;

        // Act
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $idItem, false);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, false);

        // Assert
        $this->assertCount(1, $this->touchQueryContainer->queryUpdateTouchEntry(static::TYPE_CATEGORY, $idItem));
    }

    /**
     * @return void
     */
    public function testSaveTouchRecordKeepsOneRecordIfKeyChangeTrue(): void
    {
        // Assign
        $idItem = 200000;

        // Act
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $idItem, true);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, true);

        // Assert
        $this->assertCount(1, $this->touchQueryContainer->queryUpdateTouchEntry(static::TYPE_CATEGORY, $idItem));
    }

    /**
     * @return void
     */
    public function testSaveTouchRecordKeepsTwoRecordsIfKeyChangeTrueAndEventActive(): void
    {
        // Assign
        $idItem = 300000;

        // Act
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, false);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $idItem, true);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, false);

        // Assert
        $this->assertCount(2, $this->touchQueryContainer->queryUpdateTouchEntry(static::TYPE_CATEGORY, $idItem));
    }

    /**
     * @return void
     */
    public function testSaveTouchRecordKeepsOneRecordIfKeyChangeFalseMultipleTimes(): void
    {
        // Assign
        $idItem = 400000;

        // Act
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, false);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $idItem, false);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, false);

        // Assert
        $this->assertCount(1, $this->touchQueryContainer->queryUpdateTouchEntry(static::TYPE_CATEGORY, $idItem));
    }

    /**
     * @return void
     */
    public function testSaveTouchRecordKeepsOneRecordIfKeyChangeTrueMultipleTimes(): void
    {
        // Assign
        $idItem = 500000;

        // Act
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, true);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $idItem, true);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, true);

        // Assert
        $this->assertCount(1, $this->touchQueryContainer->queryUpdateTouchEntry(static::TYPE_CATEGORY, $idItem));
    }

    /**
     * @return void
     */
    public function testSaveTouchRecordKeepsOneRecordIfKeyChangeFalseMultipleEvents(): void
    {
        // Assign
        $idItem = 600000;

        // Act
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, false);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $idItem, false);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, $idItem, false);

        // Assert
        $this->assertCount(1, $this->touchQueryContainer->queryUpdateTouchEntry(static::TYPE_CATEGORY, $idItem));
    }

    /**
     * @return void
     */
    public function testSaveTouchRecordKeepsOneRecordIfKeyChangeFalseMultipleEventsOrder(): void
    {
        // Assign
        $idItem = 700000;

        // Act
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE, $idItem, false);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $idItem, false);
        $this->touchRecord->saveTouchRecord(static::TYPE_CATEGORY, SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $idItem, false);

        // Assert
        $this->assertCount(1, $this->touchQueryContainer->queryUpdateTouchEntry(static::TYPE_CATEGORY, $idItem));
    }
}
