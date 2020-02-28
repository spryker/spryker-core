<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
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
    public function testCreateMerchantStockByMerchantSuccessful(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => 'test_merchant']);

        // Act
        $merchantTransfer = $this->tester->getFacade()
            ->createMerchantStockByMerchant($merchantTransfer)
            ->getMerchant();

        // Assert
        $this->assertGreaterThan(0, $merchantTransfer->getStockCollection()->count());
        $this->assertInstanceOf(StockTransfer::class, $merchantTransfer->getStockCollection()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testGetStocksByMerchant(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveMerchantStock($merchantTransfer->getIdMerchant(), $stockTransfer->getIdStock());

        // Act
        $stockTransfers = $this->tester->getFacade()->getStocksByMerchant($merchantTransfer);

        // Assert
        $this->assertGreaterThan(0, $stockTransfers->count());
        $this->assertEquals($stockTransfer->getIdStock(), $stockTransfers->getIterator()->current()->getIdStock());
    }

    /**
     * @return void
     */
    public function testGetStocksByMerchantEmpty(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        // Act
        $stockTransfers = $this->tester->getFacade()->getStocksByMerchant($merchantTransfer);

        // Assert
        $this->assertEquals(0, $stockTransfers->count());
        $this->assertEmpty($stockTransfers);
    }
}
