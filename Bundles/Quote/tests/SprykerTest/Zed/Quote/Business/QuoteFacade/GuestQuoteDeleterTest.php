<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
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
class GuestQuoteDeleterTest extends Unit
{
    protected const ANONYMOUS_CUSTOMER_REFERENCE = 'anonymous:123';
    protected const EMPTY_QUOTE_DATA = '{"currency":{"code":"EUR","name":"Euro","symbol":"\u20ac","isDefault":true,"fractionDigits":2},"priceMode":"GROSS_MODE"}';
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
        $this->createExpiredQuoteForCustomer($customerTransfer);

        $this->tester->setConfig(QuoteConstants::GUEST_QUOTE_LIFETIME, static::CONFIG_LIFETIME_ONE_SECOND);
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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function createExpiredQuoteForCustomer(CustomerTransfer $customerTransfer): void
    {
        $currentStoreTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $quoteQuery = SpyQuoteQuery::create();
        $quoteEntity = $quoteQuery
            ->filterByCustomerReference($customerTransfer->getCustomerReference())
            ->findOneOrCreate();
        $quoteEntity->setName('Shopping cart')
            ->setFkStore($currentStoreTransfer->getIdStore())
            ->setQuoteData(static::EMPTY_QUOTE_DATA)
            ->setCreatedAt(strtotime('-1 month'))
            ->setUpdatedAt(strtotime('-1 month'));
        $quoteEntity->save();
    }
}
