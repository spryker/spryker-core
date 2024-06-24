<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group Facade
 * @group SanitizeMerchantCommissionFromQuoteTest
 * Add your own group annotations below this line
 */
class SanitizeMerchantCommissionFromQuoteTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_AMOUNT = 100;

    /**
     * @var \SprykerTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester
     */
    protected SalesMerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldSanitizeMerchantCommissionsFromQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteWithMerchantCommissions();

        // Act
        $quoteTransfer = $this->tester->getFacade()->sanitizeMerchantCommissionFromQuote($quoteTransfer);

        // Assert
        $this->assertEmptyMerchantCommissionAmounts($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertEmptyMerchantCommissionAmounts(QuoteTransfer $quoteTransfer): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getMerchantCommissionAmountAggregation());
            $this->assertNull($itemTransfer->getMerchantCommissionAmountFullAggregation());
            $this->assertNull($itemTransfer->getMerchantCommissionRefundedAmount());
        }

        $totalsTransfer = $quoteTransfer->getTotalsOrFail();

        $this->assertNull($totalsTransfer->getMerchantCommissionTotal());
        $this->assertNull($totalsTransfer->getMerchantCommissionRefundedTotal());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteWithMerchantCommissions(): QuoteTransfer
    {
        $itemTransfer1 = (new ItemTransfer())
            ->setMerchantCommissionAmountAggregation(static::FAKE_AMOUNT)
            ->setMerchantCommissionAmountFullAggregation(static::FAKE_AMOUNT)
            ->setMerchantCommissionRefundedAmount(static::FAKE_AMOUNT);

        $itemTransfer2 = (new ItemTransfer())
            ->setMerchantCommissionAmountAggregation(static::FAKE_AMOUNT)
            ->setMerchantCommissionAmountFullAggregation(static::FAKE_AMOUNT)
            ->setMerchantCommissionRefundedAmount(static::FAKE_AMOUNT);

        $totalsTransfer = (new TotalsTransfer())
            ->setMerchantCommissionTotal(static::FAKE_AMOUNT)
            ->setMerchantCommissionRefundedTotal(static::FAKE_AMOUNT);

        return (new QuoteTransfer())
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2)
            ->setTotals($totalsTransfer);
    }
}
