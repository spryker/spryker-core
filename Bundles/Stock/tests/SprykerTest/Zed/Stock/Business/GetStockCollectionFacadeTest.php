<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Stock\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StockBuilder;
use Generated\Shared\DataBuilder\StockCriteriaBuilder;
use Generated\Shared\Transfer\StockConditionsTransfer;
use Generated\Shared\Transfer\StockTransfer;
use SprykerTest\Zed\Stock\StockBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Stock
 * @group Business
 * @group Facade
 * @group GetStockCollectionFacadeTest
 * Add your own group annotations below this line
 */
class GetStockCollectionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Stock\StockBusinessTester
     */
    protected StockBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetStockCollectionReturnsEmptyCollectionWhenNoEntityMatchedByCriteria(): void
    {
        // Arrange
        $this->tester->haveStock();
        $stockCriteriaTransfer = (new StockCriteriaBuilder())->withStockConditions([
            StockConditionsTransfer::STOCK_NAMES => [
                (new StockBuilder())->build()->getName(),
            ],
        ])->build();

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->getStockCollection($stockCriteriaTransfer);

        // Assert
        $this->assertEmpty(
            $stockCollectionTransfer->getStocks(),
            'Stocks count does not match expected value.',
        );
    }

    /**
     * @return void
     */
    public function testGetStockCollectionReturnsStocksByStockIds(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockCriteriaTransfer = (new StockCriteriaBuilder())->withStockConditions([
            StockConditionsTransfer::STOCK_IDS => [
                $stockTransfer->getIdStock(),
            ],
        ])->build();

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->getStockCollection($stockCriteriaTransfer);

        // Assert
        /**
         * @var \ArrayObject<int, \Generated\Shared\Transfer\StockTransfer> $stockTransferCollection
         */
        $stockTransferCollection = $stockCollectionTransfer->getStocks();

        $this->assertCount(
            1,
            $stockTransferCollection,
            'Stocks count does not match expected value.',
        );

        $this->assertSame(
            $stockTransfer->getIdStock(),
            $stockTransferCollection->getIterator()->current()->getIdStockOrFail(),
            'idStock does not match expected value.',
        );
    }

    /**
     * @return void
     */
    public function testGetStockCollectionReturnsStocksByActiveStatus(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock([
            StockConditionsTransfer::IS_ACTIVE => true,
        ]);
        $stockCriteriaTransfer = (new StockCriteriaBuilder())->withStockConditions([
            StockConditionsTransfer::IS_ACTIVE => $stockTransfer->getIsActive(),
        ])->build();

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->getStockCollection($stockCriteriaTransfer);

        // Assert
        /**
         * @var \ArrayObject<int, \Generated\Shared\Transfer\StockTransfer> $stockTransferCollection
         */
        $stockTransferCollection = $stockCollectionTransfer->getStocks();

        $this->assertGreaterThanOrEqual(
            1,
            $stockTransferCollection->count(),
            'Stocks count does not match expected value.',
        );

        $resultStockNames = array_map(function (StockTransfer $stockTransfer) {
            return $stockTransfer->getNameOrFail();
        }, $stockTransferCollection->getArrayCopy());

        $this->assertTrue(
            in_array($stockTransfer->getName(), $resultStockNames, true),
            'Expected stock name is missing in returned stock collection.',
        );
    }

    /**
     * @return void
     */
    public function testGetStockCollectionReturnsStocksByName(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockCriteriaTransfer = (new StockCriteriaBuilder())->withStockConditions([
            StockConditionsTransfer::STOCK_NAMES => [
                $stockTransfer->getName(),
            ],
        ])->build();

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->getStockCollection($stockCriteriaTransfer);

        // Assert
        /**
         * @var \ArrayObject<int, \Generated\Shared\Transfer\StockTransfer> $stockTransferCollection
         */
        $stockTransferCollection = $stockCollectionTransfer->getStocks();

        $this->assertCount(
            1,
            $stockTransferCollection,
            'Stocks count does not match expected value.',
        );

        $this->assertSame(
            $stockTransfer->getName(),
            $stockTransferCollection->getIterator()->current()->getNameOrFail(),
            'Stock name does not match expected value.',
        );
    }

    /**
     * @return void
     */
    public function testGetStockCollectionReturnsStocksByStoreName(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveStockStoreRelation($stockTransfer, $storeTransfer);

        $stockCriteriaTransfer = (new StockCriteriaBuilder())->withStockConditions([
            StockConditionsTransfer::STORE_NAMES => [
                $storeTransfer->getName(),
            ],
        ])->build();

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->getStockCollection($stockCriteriaTransfer);

        // Assert
        /**
         * @var \ArrayObject<int, \Generated\Shared\Transfer\StockTransfer> $stockTransferCollection
         */
        $stockTransferCollection = $stockCollectionTransfer->getStocks();

        $this->assertCount(
            1,
            $stockTransferCollection,
            'Stocks count does not match expected value.',
        );

        $this->assertSame(
            $stockTransfer->getName(),
            $stockTransferCollection->getIterator()->current()->getNameOrFail(),
            'Stock name does not match expected value.',
        );
    }
}
