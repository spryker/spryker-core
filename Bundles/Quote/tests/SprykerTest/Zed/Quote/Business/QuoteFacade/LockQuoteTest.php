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
 * @group LockQuoteTest
 * Add your own group annotations below this line
 */
class LockQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testLockQuoteShouldSetIsLockedToTrueIfQuoteIsUnlocked(): void
    {
        // Assign
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setIsLocked(false);

        // Act
        $quoteTransfer = $this->getFacade()->lockQuote($quoteTransfer);

        // Assert
        $this->assertTrue($quoteTransfer->getIsLocked());
    }

    /**
     * @return void
     */
    public function testLockQuoteShouldSetIsLockedToTrueIfQuoteIsLocked(): void
    {
        // Assign
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setIsLocked(true);

        // Act
        $quoteTransfer = $this->getFacade()->lockQuote($quoteTransfer);

        // Assert
        $this->assertTrue($quoteTransfer->getIsLocked());
    }

    /**
     * @return void
     */
    public function testUnlockQuoteShouldSetIsLockedToFalseIfQuoteIsLocked(): void
    {
        // Assign
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setIsLocked(true);

        // Act
        $quoteTransfer = $this->getFacade()->unlockQuote($quoteTransfer);

        // Assert
        $this->assertFalse($quoteTransfer->getIsLocked());
    }

    /**
     * @return void
     */
    public function testUnlockQuoteShouldSetIsLockedToFalseIfQuoteIsUnlocked(): void
    {
        // Assign
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setIsLocked(false);

        // Act
        $quoteTransfer = $this->getFacade()->unlockQuote($quoteTransfer);

        // Assert
        $this->assertFalse($quoteTransfer->getIsLocked());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
