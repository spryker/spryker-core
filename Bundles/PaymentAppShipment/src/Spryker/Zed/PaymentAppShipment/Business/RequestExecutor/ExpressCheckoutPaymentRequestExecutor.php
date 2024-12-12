<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment\Business\RequestExecutor;

use ArrayObject;
use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PaymentAppShipment\Business\Assigner\ShipmentAssignerInterface;
use Spryker\Zed\PaymentAppShipment\Business\Exception\MissingExpressCheckoutPaymentException;
use Spryker\Zed\PaymentAppShipment\Business\Exception\MissingExpressCheckoutShipmentMethodException;
use Spryker\Zed\PaymentAppShipment\Dependency\Facade\PaymentAppShipmentToShipmentFacadeInterface;
use Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig;

class ExpressCheckoutPaymentRequestExecutor implements ExpressCheckoutPaymentRequestExecutorInterface
{
    /**
     * @var string
     */
    protected const REGEX_PATTERN_PAYMENT_METHOD_KEY = '/\[(.*?)\]/';

    /**
     * @var \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig
     */
    protected PaymentAppShipmentConfig $paymentAppShipmentConfig;

    /**
     * @var \Spryker\Zed\PaymentAppShipment\Dependency\Facade\PaymentAppShipmentToShipmentFacadeInterface
     */
    protected PaymentAppShipmentToShipmentFacadeInterface $shipmentFacade;

    /**
     * @var \Spryker\Zed\PaymentAppShipment\Business\Assigner\ShipmentAssignerInterface
     */
    protected ShipmentAssignerInterface $shipmentAssigner;

    /**
     * @param \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig $paymentAppShipmentConfig
     * @param \Spryker\Zed\PaymentAppShipment\Dependency\Facade\PaymentAppShipmentToShipmentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\PaymentAppShipment\Business\Assigner\ShipmentAssignerInterface $shipmentAssigner
     */
    public function __construct(
        PaymentAppShipmentConfig $paymentAppShipmentConfig,
        PaymentAppShipmentToShipmentFacadeInterface $paymentFacade,
        ShipmentAssignerInterface $shipmentAssigner
    ) {
        $this->paymentAppShipmentConfig = $paymentAppShipmentConfig;
        $this->shipmentFacade = $paymentFacade;
        $this->shipmentAssigner = $shipmentAssigner;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer $expressCheckoutPaymentResponseTransfer
     *
     * @throws \Spryker\Zed\PaymentAppShipment\Business\Exception\MissingExpressCheckoutPaymentException
     * @throws \Spryker\Zed\PaymentAppShipment\Business\Exception\MissingExpressCheckoutShipmentMethodException
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer,
        ExpressCheckoutPaymentResponseTransfer $expressCheckoutPaymentResponseTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        if (!$expressCheckoutPaymentRequestTransfer->getQuoteOrFail()->getPayments()->offsetExists(0)) {
            throw new MissingExpressCheckoutPaymentException(
                'Express checkout payment is missing!',
            );
        }

        /** @var \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer */
        $paymentTransfer = $expressCheckoutPaymentRequestTransfer->getQuoteOrFail()->getPayments()->offsetGet(0);

        $paymentMethodKey = $this->extractPaymentMethodKey($paymentTransfer->getPaymentSelectionOrFail());
        $expressCheckoutShipmentMethodKey = $this->paymentAppShipmentConfig->getExpressCheckoutShipmentMethodsIndexedByPaymentMethod()[$paymentMethodKey] ?? '';

        $shipmentMethodTransfer = $this->shipmentFacade->findShipmentMethodByKey($expressCheckoutShipmentMethodKey);
        if (!$shipmentMethodTransfer || !$shipmentMethodTransfer->getIsActive()) {
            throw new MissingExpressCheckoutShipmentMethodException(
                sprintf('Express checkout shipment method "%s" is not available!', $expressCheckoutShipmentMethodKey),
            );
        }

        $storeTransfer = $expressCheckoutPaymentRequestTransfer->getQuoteOrFail()->getStoreOrFail();
        $shipmentMethodStores = $shipmentMethodTransfer->getStoreRelationOrFail()->getStores();
        if (!$this->isShipmentMethodAvailableForStore($storeTransfer, $shipmentMethodStores)) {
            throw new MissingExpressCheckoutShipmentMethodException(
                sprintf('Express checkout shipment method "%s" is not available for the store "%s"!', $expressCheckoutShipmentMethodKey, $storeTransfer->getNameOrFail()),
            );
        }

        $quoteTransfer = $this->addShipmentMethodToQuote(
            $expressCheckoutPaymentRequestTransfer->getQuoteOrFail(),
            $shipmentMethodTransfer,
        );

        $quoteTransfer = $this->shipmentFacade->expandQuoteWithShipmentGroups(
            $quoteTransfer->setSkipRecalculation(true),
        );

        return $expressCheckoutPaymentResponseTransfer->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addShipmentMethodToQuote(
        QuoteTransfer $quoteTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): QuoteTransfer {
        $shipmentTransfer = (new ShipmentTransfer())
            ->setShipmentSelection((string)$shipmentMethodTransfer->getIdShipmentMethod())
            ->setMethod($shipmentMethodTransfer)
            ->setShippingAddress($quoteTransfer->getCustomerOrFail()->getShippingAddress()->offsetGet(0));

        $quoteTransfer->setShipment($shipmentTransfer)
            ->setShippingAddress($shipmentTransfer->getShippingAddressOrFail());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $this->shipmentAssigner->assignShipmentToQuoteItems($quoteTransfer, $shipmentTransfer);
    }

    /**
     * @param string $paymentSelection
     *
     * @return string
     */
    protected function extractPaymentMethodKey(string $paymentSelection): string
    {
        if (preg_match(static::REGEX_PATTERN_PAYMENT_METHOD_KEY, $paymentSelection, $matches)) {
            return $matches[1];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \ArrayObject<int,\Generated\Shared\Transfer\StoreTransfer> $shipmentMethodStores
     *
     * @return bool
     */
    protected function isShipmentMethodAvailableForStore(
        StoreTransfer $storeTransfer,
        ArrayObject $shipmentMethodStores
    ): bool {
        foreach ($shipmentMethodStores as $shipmentMethodStore) {
            if ($shipmentMethodStore->getName() === $storeTransfer->getName()) {
                return true;
            }
        }

        return false;
    }
}
