<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReturnBusinessTester extends Actor
{
    use _generated\SalesReturnBusinessTesterActions;

    protected const SHIPPED_STATE_NAME = 'shipped';

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderWithFakeRemuneration(): OrderTransfer
    {
        $itemTransfers = [
            (new ItemTransfer())
                ->setRemunerationAmount(100),
            (new ItemTransfer())
                ->setRemunerationAmount(200),
            (new ItemTransfer())
                ->setRemunerationAmount(300),
        ];

        return (new OrderTransfer())
            ->setItems(new ArrayObject($itemTransfers))
            ->setTotals(new TotalsTransfer());
    }

    /**
     * @return void
     */
    public function ensureReturnReasonTablesIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesReturnReasonQuery());
    }

    /**
     * @param string $stateMachineProcessName
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer|null
     */
    public function createReturnByStateMachineProcessName(
        string $stateMachineProcessName,
        ?CustomerTransfer $customerTransfer = null
    ): ?ReturnTransfer {
        $orderTransfer = $this->createOrderByStateMachineProcessName($stateMachineProcessName, $customerTransfer);

        $firstItemTransfer = $orderTransfer->getItems()->offsetGet(0);
        $secondItemTransfer = $orderTransfer->getItems()->offsetGet(1);

        $this->setItemState($firstItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);
        $this->setItemState($secondItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($customerTransfer ?? $orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->addReturnItem((new ReturnItemTransfer())->setOrderItem($firstItemTransfer))
            ->addReturnItem((new ReturnItemTransfer())->setOrderItem($secondItemTransfer));

        return $this->getLocator()
            ->salesReturn()
            ->facade()
            ->createReturn($returnCreateRequestTransfer)
            ->getReturn();
    }

    /**
     * @param string $stateMachineProcessName
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param array|null $currencyData
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderByStateMachineProcessName(
        string $stateMachineProcessName,
        ?CustomerTransfer $customerTransfer = null,
        ?array $currencyData = []
    ): OrderTransfer {
        $quoteTransfer = $this->buildFakeQuote(
            $customerTransfer ?? $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE']),
            $currencyData
        );

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array $currencyData
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer, array $currencyData): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency($currencyData)
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery
     */
    protected function getSalesReturnReasonQuery(): SpySalesReturnReasonQuery
    {
        return SpySalesReturnReasonQuery::create();
    }
}
