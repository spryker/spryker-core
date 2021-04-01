<?php

namespace Spryker\Client\Sales\Plugin;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Client\CheckoutExtension\Dependency\Plugin\CheckoutPreCheckPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Sales\SalesClientInterface getClient()
 */
class OrderReferenceCheckoutPreCheckPlugin extends AbstractPlugin implements CheckoutPreCheckPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function isValid(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $quoteValidationResponseTransfer = new QuoteValidationResponseTransfer();

        if (!$quoteTransfer->getOrderReference()) {
            return $quoteValidationResponseTransfer->setIsSuccessful(true);
        }

        if (!$this->orderExistsInDatabase($quoteTransfer)) {
            return $quoteValidationResponseTransfer->setIsSuccessful(true);
        }

        return $quoteValidationResponseTransfer
            ->setIsSuccessful(false)
            ->setRedirectUrl('checkout-success');
    }

    protected function orderExistsInDatabase(QuoteTransfer $quoteTransfer): bool
    {
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($quoteTransfer->getOrderReference())
            ->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $orderTransfer = $this->getClient()->getCustomerOrderByOrderReference($orderTransfer);

        return (bool) $orderTransfer->getIdSalesOrder();
    }

}
