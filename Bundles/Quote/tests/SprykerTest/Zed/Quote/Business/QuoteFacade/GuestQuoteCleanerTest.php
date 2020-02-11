<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Business
 * @group QuoteFacade
 * @group GuestQuoteCleanerTest
 * Add your own group annotations below this line
 */
class GuestQuoteCleanerTest extends Unit
{
    protected const ERROR_MESSAGE_SHOULD_BE_DELETED = 'Quote should have been deleted from database.';
    protected const ERROR_MESSAGE_SHOULD_NOT_BE_DELETED = 'Quote should not have been deleted from database.';

    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGuestQuoteClearAfterLifetimeIsExceeded(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveAnonymousCustomerWithExpiredQuote();

        // Act
        $this->tester->getFacade()->deleteExpiredGuestQuote();

        // Assert
        $findQuoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomer($customerTransfer);
        $this->assertNull($findQuoteResponseTransfer->getQuoteTransfer(), static::ERROR_MESSAGE_SHOULD_BE_DELETED);
    }

    /**
     * @return void
     */
    public function testGuestQuoteNotClearedBeforeLifetimeIsExceeded(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveAnonymousCustomerWithNotExpiredQuote();

        // Act
        $this->tester->getFacade()->deleteExpiredGuestQuote();

        // Assert
        $findQuoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomer($customerTransfer);
        $this->assertNotNull($findQuoteResponseTransfer->getQuoteTransfer(), static::ERROR_MESSAGE_SHOULD_NOT_BE_DELETED);
    }
}
