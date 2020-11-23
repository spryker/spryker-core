<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Address\AddressReaderInterface;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface;

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
     * @var \Spryker\Zed\CheckoutRestApi\Business\Checkout\Address\AddressReaderInterface
     */
    protected $addressReader;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface
     */
    protected $checkoutValidator;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[]
     */
    protected $quoteMapperPlugins;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataExpanderPluginInterface[]
     */
    protected $checkoutDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Address\AddressReaderInterface $addressReader
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface $checkoutValidator
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[] $quoteMapperPlugins
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataExpanderPluginInterface[] $checkoutDataExpanderPlugins
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CheckoutRestApiToShipmentFacadeInterface $shipmentFacade,
        CheckoutRestApiToPaymentFacadeInterface $paymentFacade,
        AddressReaderInterface $addressReader,
        CheckoutRestApiToCalculationFacadeInterface $calculationFacade,
        CheckoutValidatorInterface $checkoutValidator,
        array $quoteMapperPlugins,
        array $checkoutDataExpanderPlugins
    ) {
        $this->quoteReader = $quoteReader;
        $this->shipmentFacade = $shipmentFacade;
        $this->paymentFacade = $paymentFacade;
        $this->addressReader = $addressReader;
        $this->calculationFacade = $calculationFacade;
        $this->checkoutValidator = $checkoutValidator;
        $this->quoteMapperPlugins = $quoteMapperPlugins;
        $this->checkoutDataExpanderPlugins = $checkoutDataExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    public function getCheckoutData(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutDataResponseTransfer
    {
        $quoteTransfer = $this->quoteReader->findCustomerQuoteByUuid($restCheckoutRequestAttributesTransfer);
        $restCheckoutResponseTransfer = $this->checkoutValidator->validateQuoteInCheckoutData($quoteTransfer);

        if (!$restCheckoutResponseTransfer->getIsSuccess()) {
            return $restCheckoutResponseTransfer;
        }

        $checkoutResponseTransfer = $this->checkoutValidator->validateCheckoutData($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderErrorResponse($checkoutResponseTransfer);
        }

        $quoteTransfer = $this->executeQuoteMapperPlugins($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $storeTransfer = $quoteTransfer->requireStore()
            ->getStore()
                ->requireName();

        $quoteTransfer = $this->addItemLevelShipmentTransfer($quoteTransfer);
        $quoteTransfer = $this->calculationFacade->recalculateQuote($quoteTransfer);

        $checkoutDataTransfer = (new RestCheckoutDataTransfer())
            ->setQuote($quoteTransfer)
            ->setShipmentMethods($this->getShipmentMethodsTransfer($quoteTransfer))
            ->setPaymentProviders($this->paymentFacade->getAvailablePaymentProvidersForStore($storeTransfer->getName()))
            ->setAddresses($this->addressReader->getAddressesTransfer($quoteTransfer))
            ->setAvailablePaymentMethods($this->getAvailablePaymentMethods($quoteTransfer));

        $checkoutDataTransfer = $this->executeCheckoutDataExpanderPlugins(
            $checkoutDataTransfer,
            $restCheckoutRequestAttributesTransfer
        );

        return (new RestCheckoutDataResponseTransfer())
            ->setIsSuccess(true)
            ->setCheckoutData($checkoutDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeQuoteMapperPlugins(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        foreach ($this->quoteMapperPlugins as $quoteMapperPlugin) {
            $quoteTransfer = $quoteMapperPlugin->map($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getShipmentMethodsTransfer(QuoteTransfer $quoteTransfer): ShipmentMethodsTransfer
    {
        $shipmentMethodsCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);

        if ($shipmentMethodsCollectionTransfer->getShipmentMethods()->count() === 0) {
            return new ShipmentMethodsTransfer();
        }

        /** @var \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer */
        $shipmentMethodsTransfer = $shipmentMethodsCollectionTransfer->getShipmentMethods()->getIterator()->current();

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function getAvailablePaymentMethods(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        return $this->paymentFacade->getAvailableMethods($quoteTransfer);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addItemLevelShipmentTransfer(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment()) {
                continue;
            }

            $itemTransfer->setShipment($quoteTransfer->getShipment() ?? new ShipmentTransfer());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    protected function executeCheckoutDataExpanderPlugins(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataTransfer {
        foreach ($this->checkoutDataExpanderPlugins as $checkoutDataExpanderPlugin) {
            $restCheckoutDataTransfer = $checkoutDataExpanderPlugin->expandCheckoutData(
                $restCheckoutDataTransfer,
                $restCheckoutRequestAttributesTransfer
            );
        }

        return $restCheckoutDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function createPlaceOrderErrorResponse(CheckoutResponseTransfer $checkoutResponseTransfer): RestCheckoutDataResponseTransfer
    {
        $restCheckoutDataResponseTransfer = (new RestCheckoutDataResponseTransfer())
            ->setIsSuccess(false);

        if (!$checkoutResponseTransfer->getErrors()->count()) {
            return $restCheckoutDataResponseTransfer
                ->addError(
                    (new RestCheckoutErrorTransfer())
                        ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
                );
        }

        foreach ($checkoutResponseTransfer->getErrors() as $errorTransfer) {
            $restCheckoutDataResponseTransfer->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
                    ->setDetail($errorTransfer->getMessage())
                    ->setParameters($errorTransfer->getParameters())
            );
        }

        return $restCheckoutDataResponseTransfer;
    }
}
