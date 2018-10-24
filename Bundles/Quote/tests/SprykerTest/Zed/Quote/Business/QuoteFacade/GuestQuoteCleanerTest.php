<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Quote\QuoteConstants;

/**
 * Auto-generated group annotations
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
    protected const ANONYMOUS_CUSTOMER_REFERENCE = 'anonymous:123';
    protected const CONFIG_LIFETIME_ONE_SECOND = 'PT01S';
    protected const CONFIG_LIFETIME_ONE_HOUR = 'PT01H';

    protected const MESSAGE_SHOULD_BE_DELETED = 'Quote should have been deleted from database.';
    protected const MESSAGE_SHOULD_NOT_BE_DELETED = 'Quote should not have been deleted from database.';

    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGuestQuoteClearAfterLifetimeIsExceeded(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $customerTransfer->setCustomerReference(static::ANONYMOUS_CUSTOMER_REFERENCE);

        $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $this->tester->setConfig(QuoteConstants::GUEST_QUOTE_LIFETIME, static::CONFIG_LIFETIME_ONE_SECOND);
        sleep(1);

        $this->tester->getFacade()->deleteExpiredGuestQuote();
        $findQuoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomer($customerTransfer);
        $this->assertNull($findQuoteResponseTransfer->getQuoteTransfer(), static::MESSAGE_SHOULD_BE_DELETED);
    }

    /**
     * @return void
     */
    public function testGuestQuoteNotClearedBeforeLifetimeIsExceeded(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $customerTransfer->setCustomerReference(static::ANONYMOUS_CUSTOMER_REFERENCE);

        $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $this->tester->setConfig(QuoteConstants::GUEST_QUOTE_LIFETIME, static::CONFIG_LIFETIME_ONE_HOUR);

        $this->tester->getFacade()->deleteExpiredGuestQuote();
        $findQuoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomer($customerTransfer);
        $this->assertNotNull($findQuoteResponseTransfer->getQuoteTransfer(), static::MESSAGE_SHOULD_NOT_BE_DELETED);
    }
}
