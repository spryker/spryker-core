<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutDataReader implements CheckoutDataReaderInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

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
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[]
     */
    protected $quoteMapperPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[] $quoteMapperPlugins
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CheckoutRestApiToShipmentFacadeInterface $shipmentFacade,
        CheckoutRestApiToPaymentFacadeInterface $paymentFacade,
        CheckoutRestApiToCustomerFacadeInterface $customerFacade,
        array $quoteMapperPlugins
    ) {
        $this->quoteReader = $quoteReader;
        $this->shipmentFacade = $shipmentFacade;
        $this->paymentFacade = $paymentFacade;
        $this->customerFacade = $customerFacade;
        $this->quoteMapperPlugins = $quoteMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    public function getCheckoutData(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutDataResponseTransfer
    {
        $quoteTransfer = $this->quoteReader->findCustomerQuoteByUuid($restCheckoutRequestAttributesTransfer);

        if (!$quoteTransfer) {
            return $this->createCartNotFoundErrorResponse();
        }

        foreach ($this->quoteMapperPlugins as $quoteMappingPlugin) {
            $quoteTransfer = $quoteMappingPlugin->map($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        }

        $checkoutDataTransfer = (new RestCheckoutDataTransfer())
            ->setShipmentMethods($this->getShipmentMethodsTransfer($quoteTransfer))
            ->setPaymentMethods($this->getPaymentMethodsTransfer($quoteTransfer))
            ->setAddresses($this->getAddressesTransfer($quoteTransfer));

        return (new RestCheckoutDataResponseTransfer())
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

        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($customerTransfer->getCustomerReference());
        if (!$customerResponseTransfer->getHasCustomer()) {
            return new AddressesTransfer();
        }

        return $this->extendAddressesWithDefaultBillingAndShipping($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function createCartNotFoundErrorResponse(): RestCheckoutDataResponseTransfer
    {
        return (new RestCheckoutDataResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_CART_NOT_FOUND)
                    ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function extendAddressesWithDefaultBillingAndShipping(CustomerTransfer $customerTransfer): AddressesTransfer
    {
        $addressesTransfer = $customerTransfer->getAddresses();
        foreach ($addressesTransfer->getAddresses() as $addressKey => $addressTransfer) {
            $addressesTransfer->getAddresses()->offsetGet($addressKey)
                ->setIsDefaultShipping($addressTransfer->getIdCustomerAddress() === (int)$customerTransfer->getDefaultShippingAddress());
            $addressesTransfer->getAddresses()->offsetGet($addressKey)
                ->setIsDefaultBilling($addressTransfer->getIdCustomerAddress() === (int)$customerTransfer->getDefaultBillingAddress());
        }

        return $addressesTransfer;
    }
}
