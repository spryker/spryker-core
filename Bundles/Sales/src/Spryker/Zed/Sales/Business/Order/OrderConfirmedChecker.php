<?php

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
        if ($this->successfulOrderExistsInDatabase($quoteTransfer)) {
            $checkoutResponseTransfer->getSaveOrder()->setOrderReference(
                $quoteTransfer->getOrderReference()
            );

            return false;
        }

        if ($this->failedOrderExistsInDatabase($quoteTransfer)) {
            $checkoutResponseTransfer->getSaveOrder()->setOrderReference(
                $quoteTransfer->getOrderReference()
            );
            $checkoutResponseTransfer->setIsSuccess(false);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function successfulOrderExistsInDatabase(QuoteTransfer $quoteTransfer)
    {
        return
            $quoteTransfer->getOrderReference() &&
            $quoteTransfer->getOrderConfirmed() === true &&
            $this->orderExistsInDatabase($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function failedOrderExistsInDatabase(QuoteTransfer $quoteTransfer)
    {
        return
            $quoteTransfer->getOrderReference() &&
            $quoteTransfer->getOrderConfirmed() === false &&
            $this->orderExistsInDatabase($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function orderExistsInDatabase(QuoteTransfer $quoteTransfer): bool
    {
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($quoteTransfer->getOrderReference())
            ->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $orderTransfer = $this->orderRepositoryReader->getCustomerOrderByOrderReference($orderTransfer);

        return (bool) $orderTransfer->getIdSalesOrder();
    }
}
