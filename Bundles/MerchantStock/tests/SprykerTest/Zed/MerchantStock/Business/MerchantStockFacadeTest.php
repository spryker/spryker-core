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
            ->createDefaultStockForMerchant($merchantTransfer)
            ->getMerchant();

        // Assert
        $this->assertGreaterThan(0, $merchantTransfer->getStocks()->count());
        $this->assertInstanceOf(StockTransfer::class, $merchantTransfer->getStocks()->getIterator()->current());
    }
}
