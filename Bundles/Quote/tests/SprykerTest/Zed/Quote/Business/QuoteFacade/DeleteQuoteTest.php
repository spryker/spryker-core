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
 * @group DeleteQuoteTest
 * Add your own group annotations below this line
 */
class DeleteQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteQuoteEntityFromDatabase()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = $quoteTransfer->getStore();

        // Act
        $deleteQuoteResponseTransfer = $this->tester->getFacade()->deleteQuote($quoteTransfer);

        // Assert
        $this->assertTrue($deleteQuoteResponseTransfer->getIsSuccessful(), 'Delete quote request should have been successful');
        $findQuoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomerAndStore($customerTransfer, $storeTransfer);
        $this->assertNull($findQuoteResponseTransfer->getQuoteTransfer(), 'Quote should have been deleted from database.');
    }

    /**
     * @return void
     */
    public function testDeleteForeignCustomerQuoteEntityFromDatabase()
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $customerTransfer2 = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer1,
        ]);
        $storeTransfer = $quoteTransfer->getStore();

        // Act
        $quoteTransfer->setCustomer($customerTransfer2);
        $deleteQuoteResponseTransfer = $this->tester->getFacade()->deleteQuote($quoteTransfer);

        // Assert
        $this->assertFalse($deleteQuoteResponseTransfer->getIsSuccessful(), 'Delete quote request with foreign user should not have been successful');
        $quoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomerAndStore($customerTransfer1, $storeTransfer);
        $this->assertInstanceOf(QuoteTransfer::class, $quoteResponseTransfer->getQuoteTransfer(), 'Quote should not have been deleted from database.');
    }
}
