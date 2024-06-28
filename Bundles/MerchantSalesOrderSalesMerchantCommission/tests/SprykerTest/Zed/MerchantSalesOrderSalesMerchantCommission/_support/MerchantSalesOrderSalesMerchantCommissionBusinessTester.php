<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrderSalesMerchantCommission;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotalsQuery;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\MerchantSalesOrderSalesMerchantCommissionFacadeInterface getFacade(?string $moduleName = null)
 */
class MerchantSalesOrderSalesMerchantCommissionBusinessTester extends Actor
{
    use _generated\MerchantSalesOrderSalesMerchantCommissionBusinessTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var int
     */
    protected const FAKE_MERCHANT_COMMISSION_REFUNDED_AMOUNT = 100;

    /**
     * @var int
     */
    protected const FAKE_MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION = 200;

    /**
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantOrderWithTwoItems(): MerchantOrderTransfer
    {
        $merchantTransfer = $this->haveMerchant();
        $saveOrderTransfer = $this->createOrderWithTwoItems($merchantTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        $merchantOrderTransfer = $this->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
        ]);

        /** @var \Generated\Shared\Transfer\ItemTransfer $firstItem */
        $firstItem = $saveOrderTransfer->getOrderItems()->offsetGet(0);
        $firstItem
            ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            ->setMerchantCommissionRefundedAmount(static::FAKE_MERCHANT_COMMISSION_REFUNDED_AMOUNT)
            ->setMerchantCommissionAmountFullAggregation(static::FAKE_MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION);

        /** @var \Generated\Shared\Transfer\ItemTransfer $secondItem */
        $secondItem = $saveOrderTransfer->getOrderItems()->offsetGet(1);
        $secondItem
            ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            ->setMerchantCommissionRefundedAmount(static::FAKE_MERCHANT_COMMISSION_REFUNDED_AMOUNT)
            ->setMerchantCommissionAmountFullAggregation(static::FAKE_MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION);

        $firstMerchantOrderItemTransfer = $this->haveMerchantOrderItem([
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $firstItem->getIdSalesOrderItem(),
        ])->setOrderItem($firstItem);

        $secondMerchantOrderItemTransfer = $this->haveMerchantOrderItem([
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $secondItem->getIdSalesOrderItem(),
        ])->setOrderItem($secondItem);

        $totalsTransfer = $this->haveMerchantOrderTotals($merchantOrderTransfer->getIdMerchantOrder(), [
            TotalsTransfer::MERCHANT_COMMISSION_TOTAL => static::FAKE_MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION * 2,
            TotalsTransfer::MERCHANT_COMMISSION_REFUNDED_TOTAL => 0,
        ]);

        $merchantOrderTransfer
            ->setIdOrder($saveOrderTransfer->getIdSalesOrder())
            ->setTotals($totalsTransfer)
            ->addMerchantOrderItem($firstMerchantOrderItemTransfer)
            ->addMerchantOrderItem($secondMerchantOrderItemTransfer);

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param string $stateMachine
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrderWithTwoItems(MerchantTransfer $merchantTransfer, string $stateMachine): SaveOrderTransfer
    {
        $this->configureTestStateMachine([$stateMachine]);

        $merchantReference = $merchantTransfer->getMerchantReference();
        $quoteTransfer = (new QuoteBuilder())
            ->withItem((new ItemBuilder([
                ItemTransfer::MERCHANT_REFERENCE => $merchantReference,
                ItemTransfer::MERCHANT_COMMISSION_AMOUNT_AGGREGATION => 0,
                ItemTransfer::MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION => 0,
                ItemTransfer::MERCHANT_COMMISSION_REFUNDED_AMOUNT => static::FAKE_MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION,
            ]))->build()->toArray())
            ->withItem((new ItemBuilder([
                ItemTransfer::MERCHANT_REFERENCE => $merchantReference,
                ItemTransfer::MERCHANT_COMMISSION_AMOUNT_AGGREGATION => static::FAKE_MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION,
                ItemTransfer::MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION => static::FAKE_MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION,
                ItemTransfer::MERCHANT_COMMISSION_REFUNDED_AMOUNT => 0,
            ]))->build()->toArray())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setStore($this->haveStore([StoreTransfer::NAME => 'DE']));

        return $this->haveOrderFromQuote($quoteTransfer, $stateMachine);
    }

    /**
     * @param int $idMerchantSalesOrder
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals
     */
    public function getMerchantSalesOrderTotalByIdMerchantSalesOrder(int $idMerchantSalesOrder): SpyMerchantSalesOrderTotals
    {
        return $this
            ->getMerchantSalesOrderTotalsQuery()
            ->filterByFkMerchantSalesOrder($idMerchantSalesOrder)
            ->find()
            ->getLast();
    }

    /**
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotalsQuery
     */
    protected function getMerchantSalesOrderTotalsQuery(): SpyMerchantSalesOrderTotalsQuery
    {
        return SpyMerchantSalesOrderTotalsQuery::create();
    }
}
