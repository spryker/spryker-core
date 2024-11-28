<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment\Communication\Plugin\PaymentApp;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PaymentAppExtension\Dependency\Plugin\ExpressCheckoutPaymentRequestProcessorPluginInterface;

/**
 * @method \Spryker\Zed\PaymentAppShipment\Business\PaymentAppShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig getConfig()
 */
class ShipmentExpressCheckoutPaymentRequestProcessorPlugin extends AbstractPlugin implements ExpressCheckoutPaymentRequestProcessorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Provides the shipment method for the express checkout payment request.
     * - Requires `ExpressCheckoutPaymentRequestTransfer.quote.payments` to be set.
     * - Requires `ExpressCheckoutPaymentRequestTransfer.quote.payments.paymentSelection` to be set.
     * - Requires `ExpressCheckoutPaymentRequestTransfer.quote.store` to be set.
     * - Requires `ExpressCheckoutPaymentRequestTransfer.customer.shippingAddress` to be set.
     * - Expects `ExpressCheckoutPaymentRequestTransfer.quote.items` to be set.
     * - Finds the shipment method by payment key using module config {@link \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig::getExpressCheckoutShipmentMethodsIndexedByPaymentMethod()}.
     * - Processes the express checkout payment request by adding found shipment method to the quote.
     * - Iterates over the quote items and adds the shipment to each item.
     * - Uses {@link \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig::getShipmentItemCollectionFieldNames()} to add the shipment to the quote items from the different domains.
     * - Expands quote items with shipments.
     * - Expands quote expenses with shipment expenses.
     * - Skips quote recalculation.
     * - Returns the response with the updated quote.
     * - Throws `MissingExpressCheckoutPaymentException` if the payment is missing.
     * - Throws `MissingExpressCheckoutShipmentMethodException` if the shipment method is missing.
     * - Throws `ItemShipmentSetterNotFoundExpressCheckoutException` if the shipment setter does not exist.
     * - Throws `QuoteFieldNotFoundExpressCheckoutException` if the quote field does not exist.
     * - Throws `QuoteFieldNotIterableExpressCheckoutException` if the quote field is not iterable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        return $this->getFacade()->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer);
    }
}
