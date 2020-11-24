<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Spryker\Zed\Availability\Persistence\AvailabilityPersistenceFactory;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Availability
 * @group Persistence
 * @group AvailabilityQueryContainerTest
 * Add your own group annotations below this line
 */
class AvailabilityQueryContainerTest extends Unit
{
    protected const STORE_NAME = 'Test store';
    protected const LOCALE_NAME = 'xxx';
    protected const STOCK_NAME_1 = 'Test Stock 1';
    protected const STOCK_NAME_2 = 'Test Stock 2';

    /**
     * @var \SprykerTest\Zed\Availability\AvailabilityPersistenceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer
     */
    protected $availabilityQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @var \Generated\Shared\Transfer\StockTransfer[]
     */
    protected $stockTransfers = [];

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->availabilityQueryContainer = (new AvailabilityQueryContainer())->setFactory(new AvailabilityPersistenceFactory());
        $this->localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME]);
        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);

        $this->stockTransfers[] = $this->tester->haveStock([
            StockTransfer::NAME => static::STOCK_NAME_1,
            StockTransfer::STORE_RELATION => (new StoreRelationTransfer())->addIdStores($this->storeTransfer->getIdStore()),
        ]);
        $this->stockTransfers[] = $this->tester->haveStock([
            StockTransfer::NAME => static::STOCK_NAME_2,
            StockTransfer::STORE_RELATION => (new StoreRelationTransfer())->addIdStores($this->storeTransfer->getIdStore()),
        ]);
    }

    /**
     * @return void
     */
    public function testQueryAllAvailabilityAbstractsReturnCorrectQueryObject(): void
    {
        //Act
        $query = $this->availabilityQueryContainer->queryAllAvailabilityAbstracts();

        //Assert
        $this->assertInstanceOf(SpyAvailabilityAbstractQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryAvailabilityAbstractWithStockByIdLocaleReturnsCorrectData(): void
    {
        //Arrange
        $numberOfProducts = 5;
        $productQuantity = random_int(1, 25);

        for ($i = 0; $i < $numberOfProducts; $i++) {
            $this->tester->haveProductWithStockAndAvailability($this->storeTransfer, $this->localeTransfer, $this->stockTransfers, $productQuantity);
        }

        //Act
        $queryResult = $this->availabilityQueryContainer->queryAvailabilityAbstractWithStockByIdLocale(
            $this->localeTransfer->getIdLocale(),
            $this->storeTransfer->getIdStore(),
            [static::STOCK_NAME_1, static::STOCK_NAME_2]
        )->find()->getData();

        //Assert
        $this->assertCount($numberOfProducts, $queryResult);
        $this->assertEquals($productQuantity * 2, $queryResult[0]->getVirtualColumn(AvailabilityQueryContainer::STOCK_QUANTITY));
        $this->assertEquals($productQuantity * 2, $queryResult[$numberOfProducts - 1]->getVirtualColumn(AvailabilityQueryContainer::STOCK_QUANTITY));
    }

    /**
     * @return void
     */
    public function testQueryAvailabilityWithStockByIdProductAbstractAndIdLocaleReturnsCorrectData(): void
    {
        //Arrange
        $productQuantity = random_int(1, 25);
        $productConcreteTransfer = $this->tester->haveProductWithStockAndAvailability(
            $this->storeTransfer,
            $this->localeTransfer,
            $this->stockTransfers,
            $productQuantity
        );

        //Act
        $queryResult = $this->availabilityQueryContainer->queryAvailabilityWithStockByIdProductAbstractAndIdLocale(
            $productConcreteTransfer->getFkProductAbstract(),
            $this->localeTransfer->getIdLocale(),
            $this->storeTransfer->getIdStore(),
            [static::STOCK_NAME_1, static::STOCK_NAME_2]
        )->find()->getData();

        $this->assertCount(1, $queryResult);
        $this->assertEquals($productQuantity * 2, $queryResult[0][AvailabilityQueryContainer::STOCK_QUANTITY]);
    }
}
