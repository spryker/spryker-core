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
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
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
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMappingPluginInterface[]
     */
    protected $quoteMappingPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface $customerFacade
     * @param array $quoteMappingPlugins
     */
    public function __construct(
        CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade,
        CheckoutRestApiToShipmentFacadeInterface $shipmentFacade,
        CheckoutRestApiToPaymentFacadeInterface $paymentFacade,
        CheckoutRestApiToCustomerFacadeInterface $customerFacade,
        array $quoteMappingPlugins
    ) {
        $this->cartsRestApiFacade = $cartsRestApiFacade;
        $this->shipmentFacade = $shipmentFacade;
        $this->paymentFacade = $paymentFacade;
        $this->customerFacade = $customerFacade;
        $this->quoteMappingPlugins = $quoteMappingPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutDataResponseTransfer
     */
    public function getCheckoutData(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): CheckoutDataResponseTransfer
    {
        $quoteTransfer = $this->findCustomerQuote($restCheckoutRequestAttributesTransfer);

        if (!$quoteTransfer) {
            return $this->createCartNotFoundErrorResponse();
        }

        foreach ($this->quoteMappingPlugins as $quoteMappingPlugin) {
            $quoteTransfer = $quoteMappingPlugin->mapRestRequestToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        }

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setShipmentMethods($this->getShipmentMethodsTransfer($quoteTransfer))
            ->setPaymentMethods($this->getPaymentMethodsTransfer($quoteTransfer))
            ->setAddresses($this->getAddressesTransfer($quoteTransfer));

        return (new CheckoutDataResponseTransfer())
                ->setIsSuccess(true)
                ->setCart($quoteTransfer)
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
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findCustomerQuote(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer)
    {
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($restCheckoutRequestAttributesTransfer->getCart()->getCustomer()->getCustomerReference());
        $quoteTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid(
                $restCheckoutRequestAttributesTransfer->getCart()->getId(),
                $quoteCriteriaFilterTransfer
            );
        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutDataResponseTransfer
     */
    protected function createCartNotFoundErrorResponse(): CheckoutDataResponseTransfer
    {
        return (new CheckoutDataResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new CheckoutRestApiErrorTransfer())
                    ->setErrorCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setMessage(CheckoutRestApiConfig::ERROR_MESSAGE_CART_NOT_FOUND)
            );
    }
}
