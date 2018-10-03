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
 * @group GuestCartCleanerTest
 * Add your own group annotations below this line
 */
class GuestCartCleanerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGuestCartClearAfterLifetimeIsExceeded(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $customerTransfer->setCustomerReference('anonymous:123');

        $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $this->tester->setConfig(QuoteConstants::GUEST_CART_LIFETIME, 'PT01S');
        sleep(1);

        $this->tester->getFacade()->cleanExpiredGuestCart();
        $findQuoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomer($customerTransfer);
        $this->assertNull($findQuoteResponseTransfer->getQuoteTransfer(), 'Quote should have been deleted from database.');
    }

    /**
     * @return void
     */
    public function testGuestCartNotClearedBeforeLifetimeIsExceeded(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $customerTransfer->setCustomerReference('anonymous:123');

        $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $this->tester->setConfig(QuoteConstants::GUEST_CART_LIFETIME, 'PT01H');

        $this->tester->getFacade()->cleanExpiredGuestCart();
        $findQuoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomer($customerTransfer);
        $this->assertNotNull($findQuoteResponseTransfer->getQuoteTransfer(), 'Quote should not have been deleted from database.');
    }
}
