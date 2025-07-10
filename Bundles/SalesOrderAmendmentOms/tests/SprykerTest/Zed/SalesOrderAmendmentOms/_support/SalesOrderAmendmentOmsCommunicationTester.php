<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
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
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesOrderAmendmentOmsCommunicationTester extends Actor
{
    use _generated\SalesOrderAmendmentOmsCommunicationTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'OrderAmendmentTest01';

    /**
     * @return void
     */
    public function configureOrderAmendmentTestStateMachine(): void
    {
        $xmlFolder = realpath(__DIR__ . '/../../../../_data/state-machine/');
        $this->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME], $xmlFolder);
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function haveOrderWithTwoItems(): SaveOrderTransfer
    {
        return $this->haveOrderFromQuote(
            $this->createQuoteTransfer(),
            static::DEFAULT_OMS_PROCESS_NAME,
        );
    }

    /**
     * @param int $idOrderItem
     *
     * @return string
     */
    public function getOrderItemCurrentState(int $idOrderItem): string
    {
        return $this->getSalesOrderItemQuery()
            ->joinState()
            ->filterByIdSalesOrderItem($idOrderItem)
            ->select(SpyOmsOrderItemStateTableMap::COL_NAME)
            ->findOne();
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

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withItem()
            ->withAnotherItem()
            ->withCustomer()
            ->withBillingAddress()
            ->withShippingAddress()
            ->withTotals()
            ->withStore()
            ->withCurrency()
            ->build();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }
}
