<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface;

class CheckoutDataReader implements CheckoutDataReaderInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface
     */
    protected $cartsRestApiFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface
     */
    protected $quoteCustomerExpander;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface $quoteCustomerExpander
     */
    public function __construct(
        CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade,
        CheckoutRestApiToShipmentFacadeInterface $shipmentFacade,
        CheckoutRestApiToPaymentFacadeInterface $paymentFacade,
        CheckoutRestApiToCustomerFacadeInterface $customerFacade,
        QuoteCustomerExpanderInterface $quoteCustomerExpander
    ) {
        $this->cartsRestApiFacade = $cartsRestApiFacade;
        $this->shipmentFacade = $shipmentFacade;
        $this->paymentFacade = $paymentFacade;
        $this->customerFacade = $customerFacade;
        $this->quoteCustomerExpander = $quoteCustomerExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function getCheckoutData(QuoteTransfer $quoteTransfer): CheckoutDataTransfer
    {
        $currentQuoteTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid(
                $quoteTransfer->getUuid(),
                (new QuoteCriteriaFilterTransfer())->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference())
            );

        // todo: handle the case when quote is not found.

        $currentQuoteTransfer->setBillingAddress($quoteTransfer->getBillingAddress());
        $currentQuoteTransfer->setShippingAddress($quoteTransfer->getShippingAddress());
        $currentQuoteTransfer->setPayment($quoteTransfer->getPayment());
        $currentQuoteTransfer->setShipment($quoteTransfer->getShipment());
        $currentQuoteTransfer->setCustomer($quoteTransfer->getCustomer());

        $currentQuoteTransfer = $this->quoteCustomerExpander->expandQuoteWithCustomerData($currentQuoteTransfer);

        return (new CheckoutDataTransfer())
            ->setShipmentMethods($this->getShipmentMethodsTransfer($currentQuoteTransfer))
            ->setPaymentMethods($this->getPaymentMethodsTransfer($currentQuoteTransfer))
            ->setAddresses($this->getAddressesTransfer($currentQuoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getShipmentMethodsTransfer(QuoteTransfer $quoteTransfer): ShipmentMethodsTransfer
    {
        return $this->shipmentFacade->getAvailableMethods($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function getPaymentMethodsTransfer(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        return $this->paymentFacade->getAvailableMethods($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function getAddressesTransfer(QuoteTransfer $quoteTransfer): AddressesTransfer
    {
        $customerTransfer = $quoteTransfer->getCustomer();
        if ($customerTransfer === null || $customerTransfer->getIsGuest() === true) {
            return new AddressesTransfer();
        }

        return $this->customerFacade->getAddresses($customerTransfer);
    }
}
