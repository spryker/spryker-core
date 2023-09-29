<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CheckoutDataBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\RestShipmentBuilder;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\ShipmentsRestApi\ShipmentsRestApiConfig;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacadeInterface;
use Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiDependencyProvider;

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
 * @SuppressWarnings(\SprykerTest\Zed\ShipmentsRestApi\PHPMD)
 */
class ShipmentsRestApiBusinessTester extends Actor
{
    use _generated\ShipmentsRestApiBusinessTesterActions;

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConstants::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var int
     */
    public const SHIPMENT_METHOD_ID_INVALID = -1;

    /**
     * @var array
     */
    public const SHIPMENT_METHOD = [
        ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 745,
        ShipmentMethodTransfer::STORE_CURRENCY_PRICE => 1800,
        ShipmentMethodTransfer::CURRENCY_ISO_CODE => 'EUR',
        ShipmentMethodTransfer::NAME => 'Test shipping',
        ShipmentMethodTransfer::CARRIER_NAME => 'Test carrier',
        ShipmentMethodTransfer::TAX_RATE => 19,
        ShipmentMethodTransfer::IS_ACTIVE => true,
    ];

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
        $restShipmentTransfer = (new RestShipmentBuilder([
            RestShipmentTransfer::ID_SHIPMENT_METHOD => static::SHIPMENT_METHOD[ShipmentMethodTransfer::ID_SHIPMENT_METHOD],
        ]))->build();
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
            ->withShipment([RestShipmentTransfer::ID_SHIPMENT_METHOD => static::SHIPMENT_METHOD[ShipmentMethodTransfer::ID_SHIPMENT_METHOD]])
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function prepareCheckoutDataTransferWithShipmentMethodId(): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = (new CheckoutDataBuilder([
            CheckoutDataTransfer::SHIPMENTS => [
                [ShipmentMethodTransfer::ID_SHIPMENT_METHOD => static::SHIPMENT_METHOD[ShipmentMethodTransfer::ID_SHIPMENT_METHOD]],
            ],
        ]))->build();

        return $checkoutDataTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function prepareCheckoutDataTransferWithInvalidShipmentMethodId(): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = (new CheckoutDataBuilder())->build()
            ->setShipment((new RestShipmentTransfer())->setIdShipmentMethod(static::SHIPMENT_METHOD_ID_INVALID));

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

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createShipmentExpenseTransfer(): ExpenseTransfer
    {
        return (new ExpenseBuilder([ExpenseTransfer::TYPE => ShipmentsRestApiConfig::SHIPMENT_EXPENSE_TYPE]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuote(): QuoteTransfer
    {
        return (new QuoteBuilder())->build()
            ->setPriceMode(static::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'))
            ->setStore($this->haveStore([StoreTransfer::NAME => 'DE']))
            ->addItem((new ItemBuilder())->build())
            ->addItem((new ItemBuilder())->build())
            ->addItem((new ItemBuilder())->build());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return void
     */
    public function assertSameShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): void
    {
        $this->assertSame(static::SHIPMENT_METHOD[ShipmentMethodTransfer::ID_SHIPMENT_METHOD], $shipmentMethodTransfer->getIdShipmentMethod());
        $this->assertSame(static::SHIPMENT_METHOD[ShipmentMethodTransfer::STORE_CURRENCY_PRICE], $shipmentMethodTransfer->getStoreCurrencyPrice());
        $this->assertSame(static::SHIPMENT_METHOD[ShipmentMethodTransfer::CURRENCY_ISO_CODE], $shipmentMethodTransfer->getCurrencyIsoCode());
        $this->assertSame(static::SHIPMENT_METHOD[ShipmentMethodTransfer::NAME], $shipmentMethodTransfer->getName());
        $this->assertSame(static::SHIPMENT_METHOD[ShipmentMethodTransfer::CARRIER_NAME], $shipmentMethodTransfer->getCarrierName());
        $this->assertSame(static::SHIPMENT_METHOD[ShipmentMethodTransfer::TAX_RATE], $shipmentMethodTransfer->getTaxRate());
        $this->assertSame(static::SHIPMENT_METHOD[ShipmentMethodTransfer::IS_ACTIVE], $shipmentMethodTransfer->getIsActive());
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory $shipmentsRestApiBusinessFactoryMock
     *
     * @return \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacadeInterface
     */
    public function getFacadeMock(MockObject $shipmentsRestApiBusinessFactoryMock): ShipmentsRestApiFacadeInterface
    {
        $container = new Container();
        $shipmentsRestApiDependencyProvider = new ShipmentsRestApiDependencyProvider();
        $shipmentsRestApiDependencyProvider->provideBusinessLayerDependencies($container);

        $shipmentsRestApiBusinessFactoryMock->setContainer($container);

        $shipmentsRestApiFacadeMock = $this->getFacade();
        $shipmentsRestApiFacadeMock->setFactory($shipmentsRestApiBusinessFactoryMock);

        return $shipmentsRestApiFacadeMock;
    }
}
