<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Address\AddressReaderInterface;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
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
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[]
     */
    protected $quoteMapperPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Address\AddressReaderInterface $addressReader
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[] $quoteMapperPlugins
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CheckoutRestApiToShipmentFacadeInterface $shipmentFacade,
        CheckoutRestApiToPaymentFacadeInterface $paymentFacade,
        AddressReaderInterface $addressReader,
        array $quoteMapperPlugins
    ) {
        $this->quoteReader = $quoteReader;
        $this->shipmentFacade = $shipmentFacade;
        $this->paymentFacade = $paymentFacade;
        $this->addressReader = $addressReader;
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

        $shipmentMethodsTransfer = $this->getShipmentMethodsTransferFilteredByCurrentStore($quoteTransfer);
        $selectedShipmentMethodsTransfer = $this->getSelectedShipmentMethodsTransfer($restCheckoutRequestAttributesTransfer, $shipmentMethodsTransfer);

        $checkoutDataTransfer = (new RestCheckoutDataTransfer())
            ->setShipmentMethods($shipmentMethodsTransfer)
            ->setSelectedShipmentMethods($selectedShipmentMethodsTransfer)
            ->setPaymentProviders($this->getPaymentProviders())
            ->setAddresses($this->addressReader->getAddressesTransfer($quoteTransfer))
            ->setAvailablePaymentMethods($this->getAvailablePaymentMethods($quoteTransfer));

        return (new RestCheckoutDataResponseTransfer())
                ->setIsSuccess(true)
                ->setCheckoutData($checkoutDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getShipmentMethodsTransferFilteredByCurrentStore(QuoteTransfer $quoteTransfer): ShipmentMethodsTransfer
    {
        $shipmentMethodsTransfer = $this->shipmentFacade->getAvailableMethods($quoteTransfer);

        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            $moneyValueTransfers = new ArrayObject();
            $moneyValueTransfers->append($this->findMoneyValueTransfer($shipmentMethodTransfer, $quoteTransfer->getStore()));
            $shipmentMethodTransfer->setPrices($moneyValueTransfers);
        }

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getSelectedShipmentMethodsTransfer(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        ShipmentMethodsTransfer $shipmentMethodsTransfer
    ): ShipmentMethodsTransfer {
        $selectedShipmentMethodsTransfer = new ShipmentMethodsTransfer();
        $restShipmentTransfer = $restCheckoutRequestAttributesTransfer->getShipment();

        if (!$restShipmentTransfer) {
            return $selectedShipmentMethodsTransfer;
        }

        $selectedShipmentMethodId = $restShipmentTransfer->getIdShipmentMethod();

        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $selectedShipmentMethodId) {
                $selectedShipmentMethodsTransfer->addMethod($shipmentMethodTransfer);
                break;
            }
        }

        return $selectedShipmentMethodsTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    protected function getPaymentProviders(): PaymentProviderCollectionTransfer
    {
        return $this->paymentFacade->getAvailablePaymentProviders();
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
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function createCartNotFoundErrorResponse(): RestCheckoutDataResponseTransfer
    {
        return (new RestCheckoutDataResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    protected function findMoneyValueTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): ?MoneyValueTransfer {
        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            if ($this->isMoneyValueTransferForCurrentStoreAndCurrency(
                $moneyValueTransfer,
                $storeTransfer,
                $shipmentMethodTransfer
            )) {
                return $moneyValueTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function isMoneyValueTransferForCurrentStoreAndCurrency(
        MoneyValueTransfer $moneyValueTransfer,
        StoreTransfer $storeTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): bool {
        return $moneyValueTransfer->getFkStore() === $storeTransfer->getIdStore()
            && $moneyValueTransfer->getCurrency()->getCode() === $shipmentMethodTransfer->getCurrencyIsoCode();
    }
}
