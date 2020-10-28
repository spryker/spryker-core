<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CheckoutDataBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\RestShipmentBuilder;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

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
 * @method \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentsRestApiBusinessTester extends Actor
{
    use _generated\ShipmentsRestApiBusinessTesterActions;

    public const SHIPMENT_METHOD = [
        'idShipmentMethod' => 745,
        'storeCurrencyPrice' => 1800,
        'currencyIsoCode' => 'EUR',
        'name' => 'Test shipping',
        'carrierName' => 'Test carrier',
        'taxRate' => 19,
        'isActive' => true,
    ];

    public const SHIPMENT_METHOD_ID_INVALID = -1;

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareRestCheckoutRequestAttributesTransferWithoutShipment(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareRestCheckoutRequestAttributesTransferWithShipment(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())->build();
        $restShipmentTransfer = (new RestShipmentBuilder(['idShipmentMethod' => static::SHIPMENT_METHOD['idShipmentMethod']]))->build();
        $restCheckoutRequestAttributesTransfer->setShipment($restShipmentTransfer);

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareRestCheckoutRequestAttributesTransferWithItemLevelShipment(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withShipment(['idShipmentMethod' => static::SHIPMENT_METHOD['idShipmentMethod']])
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function prepareCheckoutDataTransferWithShipmentMethodId(): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withShipment(['idShipmentMethod' => static::SHIPMENT_METHOD['idShipmentMethod']])
            ->build();

        return $checkoutDataTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function prepareCheckoutDataTransferWithInvalidShipmentMethodId(): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withShipment(['idShipmentMethod' => static::SHIPMENT_METHOD_ID_INVALID])
            ->build();

        return $checkoutDataTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function prepareCheckoutDataTransferWithoutShipment(): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->build();

        return $checkoutDataTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransfer(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())->build();

        return $quoteTransfer;
    }
}
