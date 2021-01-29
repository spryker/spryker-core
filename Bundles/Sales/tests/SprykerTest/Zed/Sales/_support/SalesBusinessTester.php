<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales;

use Codeception\Actor;
use Generated\Shared\DataBuilder\OrderListRequestBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
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
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesBusinessTester extends Actor
{
    use _generated\SalesBusinessTesterActions;

    /**
     * @param string $stateMachineProcessName
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderByStateMachineProcessName(
        string $stateMachineProcessName,
        ?CustomerTransfer $customerTransfer = null
    ): OrderTransfer {
        $quoteTransfer = $this->buildFakeQuote(
            $customerTransfer ?? $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE'])
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
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\OrderListRequestTransfer
     */
    public function createOrderListRequestTransfer(array $seed): OrderListRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer */
        $orderListRequestTransfer = (new OrderListRequestBuilder($seed))
            ->build();

        return $orderListRequestTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuote(array $seed = []): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder($seed))
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        return $quoteTransfer;
    }
}
