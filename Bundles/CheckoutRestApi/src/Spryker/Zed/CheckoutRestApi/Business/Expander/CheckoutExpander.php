<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Address\AddressReaderInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface;

class CheckoutExpander implements CheckoutExpanderInterface
{
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
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataExpanderPluginInterface[]
     */
    protected $checkoutDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Address\AddressReaderInterface $addressReader
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataExpanderPluginInterface[] $checkoutDataExpanderPlugins
     */
    public function __construct(
        CheckoutRestApiToShipmentFacadeInterface $shipmentFacade,
        CheckoutRestApiToPaymentFacadeInterface $paymentFacade,
        AddressReaderInterface $addressReader,
        array $checkoutDataExpanderPlugins
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->paymentFacade = $paymentFacade;
        $this->addressReader = $addressReader;
        $this->checkoutDataExpanderPlugins = $checkoutDataExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function expandCheckoutData(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataTransfer {
        $restCheckoutDataTransfer = $this->expandCheckoutDataWithShipmentMethods($restCheckoutDataTransfer);
        $restCheckoutDataTransfer = $this->expandCheckoutDataWithPaymentProviders($restCheckoutDataTransfer);
        $restCheckoutDataTransfer = $this->expandCheckoutDataWithAddresses($restCheckoutDataTransfer);
        $restCheckoutDataTransfer = $this->expandCheckoutDataWithAvailablePaymentMethods($restCheckoutDataTransfer);

        $restCheckoutDataTransfer = $this->executeCheckoutDataExpanderPlugins(
            $restCheckoutDataTransfer,
            $restCheckoutRequestAttributesTransfer
        );

        return $restCheckoutDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    protected function expandCheckoutDataWithShipmentMethods(
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): RestCheckoutDataTransfer {
        $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
        $shipmentMethodsCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment(
            $restCheckoutDataTransfer->getQuote()
        );

        if ($shipmentMethodsCollectionTransfer->getShipmentMethods()->count()) {
            $shipmentMethodsTransfer = $shipmentMethodsCollectionTransfer->getShipmentMethods()->getIterator()->current();
        }

        return $restCheckoutDataTransfer->setShipmentMethods($shipmentMethodsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    protected function expandCheckoutDataWithPaymentProviders(
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): RestCheckoutDataTransfer {
        $storeName = $restCheckoutDataTransfer->getQuote()->getStore()->getName();
        $paymentProviderCollectionTransfer = $this->paymentFacade->getAvailablePaymentProvidersForStore($storeName);

        return $restCheckoutDataTransfer->setPaymentProviders($paymentProviderCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    protected function expandCheckoutDataWithAddresses(
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): RestCheckoutDataTransfer {
        $addressesTransfer = $this->addressReader->getAddressesTransfer(
            $restCheckoutDataTransfer->getQuote()
        );

        return $restCheckoutDataTransfer->setAddresses($addressesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    protected function expandCheckoutDataWithAvailablePaymentMethods(
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): RestCheckoutDataTransfer {
        $paymentMethodsTransfer = $this->paymentFacade->getAvailableMethods(
            $restCheckoutDataTransfer->getQuote()
        );

        return $restCheckoutDataTransfer->setAvailablePaymentMethods($paymentMethodsTransfer);
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
}
