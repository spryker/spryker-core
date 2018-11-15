<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CheckoutDataResponseTransfer;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutRestApiErrorTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface;
use Spryker\Zed\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

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
     * @return \Generated\Shared\Transfer\CheckoutDataResponseTransfer
     */
    public function getCheckoutData(QuoteTransfer $quoteTransfer): CheckoutDataResponseTransfer
    {
        $currentQuoteTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid(
                $quoteTransfer->getUuid(),
                (new QuoteCriteriaFilterTransfer())->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference())
            );

        if (!$currentQuoteTransfer) {
            return (new CheckoutDataResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new CheckoutRestApiErrorTransfer())
                        ->setErrorCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                        ->setMessage(CheckoutRestApiConfig::ERROR_MESSAGE_CART_NOT_FOUND)
                );
        }

        $currentQuoteTransfer = $this->mergeSavedQuoteWithIncomingQuote($quoteTransfer, $currentQuoteTransfer);

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setShipmentMethods($this->getShipmentMethodsTransfer($currentQuoteTransfer))
            ->setPaymentMethods($this->getPaymentMethodsTransfer($currentQuoteTransfer))
            ->setAddresses($this->getAddressesTransfer($currentQuoteTransfer));

        return (new CheckoutDataResponseTransfer())
                ->setIsSuccess(true)
                ->setCheckoutData($checkoutDataTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeSavedQuoteWithIncomingQuote(QuoteTransfer $quoteTransfer, QuoteTransfer $currentQuoteTransfer): QuoteTransfer
    {
        $currentQuoteTransfer->setBillingAddress($quoteTransfer->getBillingAddress());
        $currentQuoteTransfer->setShippingAddress($quoteTransfer->getShippingAddress());
        $currentQuoteTransfer->setPayment($quoteTransfer->getPayment());
        $currentQuoteTransfer->setShipment($quoteTransfer->getShipment());
        $currentQuoteTransfer->setCustomer($quoteTransfer->getCustomer());

        $currentQuoteTransfer = $this->quoteCustomerExpander->expandQuoteWithCustomerData($currentQuoteTransfer);
        return $currentQuoteTransfer;
    }
}
