<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Business
 * @group QuoteFacade
 * @group ReadQuoteTest
 * Add your own group annotations below this line
 */
class ReadQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testReadQuoteFromDatabaseByCustomer()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = $quoteTransfer->getStore();

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomerAndStore($customerTransfer, $storeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful(), 'Quote response transfer should have ben successful.');
        $this->assertEquals($quoteTransfer->getIdQuote(), $quoteResponseTransfer->getQuoteTransfer()->getIdQuote(), 'Quote response should have expected quote ID from database.');
    }
}
