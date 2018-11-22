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
    protected const WRONG_UUID = 'wrong-uuid-1';

    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testReadQuoteFromDatabaseByCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        // Act
        /** @var \Spryker\Zed\Quote\Business\QuoteFacadeInterface $quoteFacade */
        $quoteFacade = $this->tester->getFacade();
        $quoteResponseTransfer = $quoteFacade->findQuoteByCustomer($customerTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful(), 'Quote response transfer should have ben successful.');
        $this->assertEquals($quoteTransfer->getIdQuote(), $quoteResponseTransfer->getQuoteTransfer()->getIdQuote(), 'Quote response should have expected quote ID from database.');
    }

    /**
     * @return void
     */
    public function testReadQuoteFromDatabaseByUuid(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        // Act
        /** @var \Spryker\Zed\Quote\Business\QuoteFacadeInterface $quoteFacade */
        $quoteFacade = $this->tester->getFacade();
        $quoteResponseTransfer = $quoteFacade->findQuoteByUuid($quoteTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful(), 'Quote search should have been successful.');
        $this->assertNotNull($quoteResponseTransfer->getQuoteTransfer(), 'Quote response should have quote.');
        $this->assertEquals($quoteTransfer->getIdQuote(), $quoteResponseTransfer->getQuoteTransfer()->getIdQuote(), 'Quote response expected quote ID from database.');
    }

    /**
     * @return void
     */
    public function testFailToReadQuoteFromDatabaseByUuid(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $quoteTransfer->setUuid(static::WRONG_UUID);

        // Act
        /** @var \Spryker\Zed\Quote\Business\QuoteFacadeInterface $quoteFacade */
        $quoteFacade = $this->tester->getFacade();
        $quoteResponseTransfer = $quoteFacade->findQuoteByUuid($quoteTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful(), 'Quote search should have failed.');
        $this->assertNull($quoteResponseTransfer->getQuoteTransfer(), 'Quote response should have no quote.');
    }
}
