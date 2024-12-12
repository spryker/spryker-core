<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentAppShipment;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ExpressCheckoutPaymentRequestBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\Payment\PHPMD)
 *
 * @method \Spryker\Zed\PaymentAppShipment\Business\PaymentAppShipmentFacadeInterface getFacade()
 */
class PaymentAppShipmentBusinessTester extends Actor
{
    use _generated\PaymentAppShipmentBusinessTesterActions;

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer
     */
    public function haveExpressCheckoutPaymentRequestTransfer(array $seedData = []): ExpressCheckoutPaymentRequestTransfer
    {
        return (new ExpressCheckoutPaymentRequestBuilder($seedData))
            ->build();
    }

    /**
     * @param array<string, mixed> $seedData
     * @param array<string, mixed> $paymentSeedData
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function haveQuoteTransfer(array $seedData = [], array $paymentSeedData = []): QuoteTransfer
    {
        return (new QuoteBuilder($seedData))
            ->withItem()
            ->withStore()
            ->withBundleItem()
            ->withPayment($paymentSeedData)
            ->withCustomer((new CustomerBuilder())->withShippingAddress())
            ->build();
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function haveShipmentMethodTransfer(array $seedData = []): ShipmentMethodTransfer
    {
        return (new ShipmentMethodBuilder($seedData + [
            ShipmentMethodTransfer::IS_ACTIVE => true,
            ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'dummy',
        ]))->withStoreRelation()->build();
    }
}
