<?php

namespace Spryker\Zed\Sales\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 */
class OrderConfirmedCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
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

    protected function successfulOrderExistsInDatabase(QuoteTransfer $quoteTransfer)
    {
        return
            $quoteTransfer->getOrderReference() &&
            $quoteTransfer->getOrderConfirmed() === true &&
            $this->orderExistsInDatabase($quoteTransfer);
    }

    protected function failedOrderExistsInDatabase(QuoteTransfer $quoteTransfer)
    {
        return
            $quoteTransfer->getOrderReference() &&
            $quoteTransfer->getOrderConfirmed() === false &&
            $this->orderExistsInDatabase($quoteTransfer);
    }

    protected function orderExistsInDatabase(QuoteTransfer $quoteTransfer): bool
    {
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($quoteTransfer->getOrderReference())
            ->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $orderTransfer = $this->getFacade()->getCustomerOrderByOrderReference($orderTransfer);

        return (bool) $orderTransfer->getIdSalesOrder();
    }

}
