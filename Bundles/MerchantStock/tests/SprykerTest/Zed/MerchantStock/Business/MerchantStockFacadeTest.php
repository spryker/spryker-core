<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\StockTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantStock
 * @group Business
 * @group Facade
 * @group MerchantStockFacadeTest
 * Add your own group annotations below this line
 */
class MerchantStockFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantStock\MerchantStockBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateDefaultMerchantStockSuccessful(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        // Act
        $merchantTransfer = $this->tester->getFacade()
            ->createDefaultMerchantStock($merchantTransfer)
            ->getMerchant();

        // Assert
        $this->assertNotEmpty($merchantTransfer->getStocks());
        $this->assertInstanceOf(StockTransfer::class, $merchantTransfer->getStocks()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testGetReturnsRelatedStocks(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveMerchantStock([
            MerchantStockTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantStockTransfer::ID_STOCK => $stockTransfer->getIdStock(),
        ]);

        $merchantStockCriteriaTransfer = (new MerchantStockCriteriaTransfer())
            ->setIdMerchant($merchantTransfer->getIdMerchant());

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->get($merchantStockCriteriaTransfer);

        // Assert
        $this->assertNotEmpty($stockCollectionTransfer->getStocks());
        $this->assertSame($stockTransfer->getIdStock(), $stockCollectionTransfer->getStocks()->getIterator()->current()->getIdStock());
    }

    /**
     * @return void
     */
    public function testGetReturnsEmptyStocks(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $merchantStockCriteriaTransfer = (new MerchantStockCriteriaTransfer())
            ->setIdMerchant($merchantTransfer->getIdMerchant());

        // Act
        $stockCollectionTransfer = $this->tester->getFacade()->get($merchantStockCriteriaTransfer);

        // Assert
        $this->assertEmpty($stockCollectionTransfer->getStocks());
    }
}
