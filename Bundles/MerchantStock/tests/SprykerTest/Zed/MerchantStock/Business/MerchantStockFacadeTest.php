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
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchantStockByMerchant($merchantTransfer);
        $merchantTransfer = $merchantResponseTransfer->getMerchant();

        // Assert
        $this->assertGreaterThan(0, $merchantTransfer->getStockCollection()->count());
        $this->assertInstanceOf(StockTransfer::class, $merchantTransfer->getStockCollection()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testExpandMerchantWithStocks(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveMerchantStock($merchantTransfer->getIdMerchant(), $stockTransfer->getIdStock());

        // Act
        $merchantTransfer = $this->tester->getFacade()->expandMerchantWithStocks($merchantTransfer);

        // Assert
        $this->assertGreaterThan(0, $merchantTransfer->getStockCollection()->count());
        $this->assertEquals($stockTransfer->getIdStock(), $merchantTransfer->getStockCollection()->getIterator()->current()->getIdStock());
    }
}
