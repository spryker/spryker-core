<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment\Business\Assigner;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\PaymentAppShipment\Business\Exception\ItemShipmentSetterNotFoundExpressCheckoutException;
use Spryker\Zed\PaymentAppShipment\Business\Exception\QuoteFieldNotFoundExpressCheckoutException;
use Spryker\Zed\PaymentAppShipment\Business\Exception\QuoteFieldNotIterableExpressCheckoutException;
use Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig;

class ShipmentAssigner implements ShipmentAssignerInterface
{
    /**
     * @var \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig
     */
    protected PaymentAppShipmentConfig $paymentAppShipmentConfig;

    /**
     * @param \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig $paymentAppShipmentConfig
     */
    public function __construct(PaymentAppShipmentConfig $paymentAppShipmentConfig)
    {
        $this->paymentAppShipmentConfig = $paymentAppShipmentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function assignShipmentToQuoteItems(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer
    ): QuoteTransfer {
        $fieldNames = $this->paymentAppShipmentConfig->getShipmentItemCollectionFieldNames();

        foreach ($fieldNames as $field) {
            $itemTransfers = $this->getItemTransfersFromQuote($quoteTransfer, $field);
            $this->assignShipmentToItems($itemTransfers, $shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $field
     *
     * @throws \Spryker\Zed\PaymentAppShipment\Business\Exception\QuoteFieldNotFoundExpressCheckoutException
     * @throws \Spryker\Zed\PaymentAppShipment\Business\Exception\QuoteFieldNotIterableExpressCheckoutException
     *
     * @return iterable<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersFromQuote(QuoteTransfer $quoteTransfer, string $field): iterable
    {
        if (!$quoteTransfer->offsetExists($field)) {
            throw new QuoteFieldNotFoundExpressCheckoutException(sprintf('The field "%s" does not exist on %s', $field, get_class($quoteTransfer)));
        }

        $itemTransfers = $quoteTransfer->offsetGet($field);
        if (!is_iterable($itemTransfers)) {
            throw new QuoteFieldNotIterableExpressCheckoutException(sprintf('The field "%s" is not iterable on %s', $field, get_class($quoteTransfer)));
        }

        return $itemTransfers;
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @throws \Spryker\Zed\PaymentAppShipment\Business\Exception\ItemShipmentSetterNotFoundExpressCheckoutException
     *
     * @return void
     */
    protected function assignShipmentToItems(iterable $itemTransfers, ShipmentTransfer $shipmentTransfer): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            if (!method_exists($itemTransfer, 'setShipment')) {
                throw new ItemShipmentSetterNotFoundExpressCheckoutException(sprintf('The method "setShipment" does not exist on %s', get_class($itemTransfer)));
            }

            $itemTransfer->setShipment($shipmentTransfer);
        }
    }
}
