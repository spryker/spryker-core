<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Shared\Quote\QuoteConstants;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\Quote\Business\QuoteFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class QuoteBusinessTester extends Actor
{
    use _generated\QuoteBusinessTesterActions;

   /**
    * Define custom actions here
    */

    protected const ANONYMOUS_CUSTOMER_REFERENCE = 'anonymous:123';
    protected const CONFIG_LIFETIME_ONE_SECOND = 'PT01S';
    protected const CONFIG_LIFETIME_ONE_HOUR = 'PT01H';

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveAnonymousCustomerWithNotExpiredQuote(): CustomerTransfer
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setCustomerReference(static::ANONYMOUS_CUSTOMER_REFERENCE);
        $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $this->setConfig(QuoteConstants::GUEST_QUOTE_LIFETIME, static::CONFIG_LIFETIME_ONE_HOUR);

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveAnonymousCustomerWithExpiredQuote(): CustomerTransfer
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setCustomerReference(static::ANONYMOUS_CUSTOMER_REFERENCE);
        $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $this->createExpiredGuestQuote($customerTransfer);
        $this->setConfig(QuoteConstants::GUEST_QUOTE_LIFETIME, static::CONFIG_LIFETIME_ONE_SECOND);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function createExpiredGuestQuote(CustomerTransfer $customerTransfer): void
    {
        $quoteQuery = SpyQuoteQuery::create();
        $quoteEntity = $quoteQuery
            ->filterByCustomerReference($customerTransfer->getCustomerReference())
            ->findOne();
        $quoteEntity
            ->setUpdatedAt(strtotime('-1 month'))
            ->save();
    }
}
