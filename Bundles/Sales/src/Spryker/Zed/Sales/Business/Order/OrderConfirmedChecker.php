<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReaderInterface;

class OrderConfirmedChecker implements OrderConfirmedCheckerInterface
{
    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReaderInterface
     */
    protected $orderRepositoryReader;

    /**
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReaderInterface $orderRepositoryReader
     */
    public function __construct(OrderRepositoryReaderInterface $orderRepositoryReader)
    {
        $this->orderRepositoryReader = $orderRepositoryReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkConfirmedOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if ($this->isOrderExists($quoteTransfer)) {
            $this->setCheckoutResponseData($quoteTransfer, $checkoutResponseTransfer);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isOrderExists(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getOrderReference()) {
            return false;
        }

        if ($quoteTransfer->getIsOrderPlacedSuccessfully() === null) {
            return false;
        }

        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($quoteTransfer->getOrderReference())
            ->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $orderTransfer = $this->orderRepositoryReader->getCustomerOrderByOrderReference($orderTransfer);

        return (bool)$orderTransfer->getIdSalesOrder();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function setCheckoutResponseData(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $checkoutResponseTransfer->getSaveOrder()->setOrderReference(
            $quoteTransfer->getOrderReference()
        );

        if ($quoteTransfer->getIsOrderPlacedSuccessfully() === false) {
            $checkoutResponseTransfer->setIsSuccess(false);
        }
    }
}
