<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartReorder;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

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
 * @method \Spryker\Zed\CartReorder\Business\CartReorderFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CartReorderBusinessTester extends Actor
{
    use _generated\CartReorderBusinessTesterActions;

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrder(?CustomerTransfer $customerTransfer = null): OrderTransfer
    {
        $customerTransfer = $customerTransfer ?? $this->haveCustomer();
        $customerReference = $customerTransfer->getCustomerReferenceOrFail();

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::CUSTOMER_REFERENCE => $customerReference]))
            ->withStore()
            ->withItem([ItemTransfer::GROUP_KEY => 'item1', ItemTransfer::SKU => 'sku1'])
            ->withItem([ItemTransfer::GROUP_KEY => 'item2', ItemTransfer::SKU => 'sku2'])
            ->withItem([ItemTransfer::GROUP_KEY => 'item3', ItemTransfer::SKU => 'sku3'])
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerReference])
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($customerTransfer)
            ->setCustomerReference($customerReference)
            ->setItems($saveOrderTransfer->getOrderItems());
    }
}
