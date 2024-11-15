<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;

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
class SalesOrderAmendmentOmsBusinessTester extends Actor
{
    use _generated\SalesOrderAmendmentOmsBusinessTesterActions;

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'OrderAmendmentTest01';

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
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getSalesOrderAmendmentOmsOmsFacadeMock(): SalesOrderAmendmentOmsToOmsFacadeInterface
    {
        $omsFacadeMock = Stub::makeEmpty(SalesOrderAmendmentOmsToOmsFacadeInterface::class);
        $omsFacadeMock->expects(new InvokedCount(0))->method('triggerEventForOrderItems');
        $omsFacadeMock->expects(new InvokedCount(0))->method('areOrderItemsSatisfiedByFlag');

        return $omsFacadeMock;
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
