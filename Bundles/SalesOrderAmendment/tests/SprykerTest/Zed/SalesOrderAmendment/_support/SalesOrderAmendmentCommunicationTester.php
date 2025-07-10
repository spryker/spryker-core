<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\SalesOrderAmendment\Persistence\Base\SpySalesOrderAmendmentQuote;
use Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuoteQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesOrderAmendmentCommunicationTester extends Actor
{
    use _generated\SalesOrderAmendmentCommunicationTesterActions;

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(CustomerTransfer $customerTransfer): QuoteTransfer
    {
        return (new QuoteBuilder([QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReferenceOrFail()]))
            ->withStore()
            ->withItem()
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReferenceOrFail()])
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();
    }

    /**
     * @param string $amendmentOrderReference
     *
     * @return \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuote|null
     */
    public function findSalesOrderAmendmentQuoteByAmendmentOrderReference(string $amendmentOrderReference): ?SpySalesOrderAmendmentQuote
    {
        return $this->getSalesOrderAmendmentQuoteQuery()
            ->filterByAmendmentOrderReference($amendmentOrderReference)
            ->findOne();
    }

    /**
     * @param string $amendmentOrderReference
     *
     * @return int
     */
    public function countSalesOrderAmendmentQuoteByAmendmentOrderReference(string $amendmentOrderReference): int
    {
        return $this->getSalesOrderAmendmentQuoteQuery()
            ->filterByAmendmentOrderReference($amendmentOrderReference)
            ->count();
    }

    /**
     * @return void
     */
    public function ensureSalesOrderAmendmentQuoteTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesOrderAmendmentQuoteQuery());
    }

    /**
     * @return \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuoteQuery
     */
    protected function getSalesOrderAmendmentQuoteQuery(): SpySalesOrderAmendmentQuoteQuery
    {
        return SpySalesOrderAmendmentQuoteQuery::create();
    }
}
