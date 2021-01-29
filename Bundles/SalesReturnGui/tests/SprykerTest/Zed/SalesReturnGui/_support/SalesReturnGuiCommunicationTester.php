<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturnGui;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReturnGuiCommunicationTester extends Actor
{
    use _generated\SalesReturnGuiCommunicationTesterActions;

    protected const SHIPPED_STATE_NAME = 'shipped';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer|null
     */
    public function createReturnByStateMachineProcessName(
        OrderTransfer $orderTransfer,
        ?CustomerTransfer $customerTransfer = null
    ): ?ReturnTransfer {
        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($customerTransfer ?? $orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore());

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

            $returnCreateRequestTransfer->addReturnItem((new ReturnItemTransfer())->setOrderItem($itemTransfer));
        }

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
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
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
