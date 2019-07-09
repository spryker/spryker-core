<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutDataBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeBridge;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShipmentsRestApi
 * @group Business
 * @group Facade
 * @group ShipmentsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentsRestApiFacadeTest extends Unit
{
    protected const SHIPMENT_METHOD = [
        'idShipmentMethod' => 745,
        'storeCurrencyPrice' => 1800,
        'currencyIsoCode' => 'EUR',
        'name' => 'Test shipping',
        'carrierName' => 'Test carrier',
        'taxRate' => 19,
        'isActive' => true,
    ];

    protected const SHIPMENT_METHOD_ID_INVALID = -1;

    /**
     * @var \SprykerTest\Zed\ShipmentsRestApi\ShipmentsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnShipmentProvided(): void
    {
        /** @var \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacade $shipmentRestApiFacade */
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->prepareRestCheckoutRequestAttributesTransferWithShipment();
        $quoteTransfer = $this->prepareQuoteTransfer();

        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNotNull($actualQuote->getShipment());
        $this->assertGreaterThan(0, $actualQuote->getExpenses()->count());
        $actualShipmentMethodTransfer = $actualQuote->getShipment()->getMethod();
        $this->assertEquals(static::SHIPMENT_METHOD['idShipmentMethod'], $actualShipmentMethodTransfer->getIdShipmentMethod());
        $this->assertEquals(static::SHIPMENT_METHOD['storeCurrencyPrice'], $actualShipmentMethodTransfer->getStoreCurrencyPrice());
        $this->assertEquals(static::SHIPMENT_METHOD['currencyIsoCode'], $actualShipmentMethodTransfer->getCurrencyIsoCode());
        $this->assertEquals(static::SHIPMENT_METHOD['name'], $actualShipmentMethodTransfer->getName());
        $this->assertEquals(static::SHIPMENT_METHOD['carrierName'], $actualShipmentMethodTransfer->getCarrierName());
        $this->assertEquals(static::SHIPMENT_METHOD['taxRate'], $actualShipmentMethodTransfer->getTaxRate());
        $this->assertEquals(static::SHIPMENT_METHOD['isActive'], $actualShipmentMethodTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnNoShipmentProvided(): void
    {
        /** @var \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacade $shipmentRestApiFacade */
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->prepareRestCheckoutRequestAttributesTransferWithoutShipment();
        $quoteTransfer = $this->prepareQuoteTransfer();

        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getShipment());
        $this->assertCount(0, $actualQuote->getExpenses());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnShipmentNotFound(): void
    {
        /** @var \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacade $shipmentRestApiFacade */
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactoryWithShipmentNotFound());

        $restCheckoutRequestAttributesTransfer = $this->prepareRestCheckoutRequestAttributesTransferWithShipment();
        $quoteTransfer = $this->prepareQuoteTransfer();

        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getShipment());
        $this->assertCount(0, $actualQuote->getExpenses());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillValidateShipmentMethodCheckoutData(): void
    {
        /** @var \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacade $shipmentRestApiFacade */
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $checkoutDataTransfer = $this->prepareCheckoutDataTransferWithShipmentMethodId();
        $checkoutResponseTransfer = $shipmentRestApiFacade->validateShipmentMethodCheckoutData($checkoutDataTransfer);

        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEquals(0, $checkoutResponseTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillValidateShipmentMethodCheckoutDataWithInvalidShipmentMethodId(): void
    {
        /** @var \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacade $shipmentRestApiFacade */
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactoryWithShipmentNotFound());

        $checkoutDataTransfer = $this->prepareCheckoutDataTransferWithInvalidShipmentMethodId();
        $checkoutResponseTransfer = $shipmentRestApiFacade->validateShipmentMethodCheckoutData($checkoutDataTransfer);

        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertGreaterThan(0, $checkoutResponseTransfer->getErrors()->count());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShipmentsRestApiFactory(): MockObject
    {
        $mockFactory = $this->createPartialMock(
            ShipmentsRestApiBusinessFactory::class,
            ['getShipmentFacade']
        );

        $mockFactory->method('getShipmentFacade')
            ->willReturn(new ShipmentsRestApiToShipmentFacadeBridge($this->getMockShipmentFacade()));

        return $mockFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShipmentFacade(): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            ShipmentFacade::class,
            [
                'findAvailableMethodById',
                'findMethodById',
            ]
        );

        $mockCustomerFacade->method('findAvailableMethodById')
            ->willReturn(
                (new ShipmentMethodBuilder(static::SHIPMENT_METHOD))->withPrice()->build()
            );

        $mockCustomerFacade->method('findMethodById')
            ->willReturn(
                (new ShipmentMethodBuilder(static::SHIPMENT_METHOD))->build()
            );

        return $mockCustomerFacade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShipmentsRestApiFactoryWithShipmentNotFound(): MockObject
    {
        $mockFactory = $this->createPartialMock(
            ShipmentsRestApiBusinessFactory::class,
            ['getShipmentFacade']
        );

        $mockFactory->method('getShipmentFacade')
            ->willReturn(new ShipmentsRestApiToShipmentFacadeBridge($this->getMockShipmentFacadeWithShipmentNotFound()));

        return $mockFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShipmentFacadeWithShipmentNotFound(): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            ShipmentFacade::class,
            [
                'findAvailableMethodById',
                'findMethodById',
            ]
        );

        $mockCustomerFacade->method('findAvailableMethodById')
            ->willReturn(null);

        $mockCustomerFacade->method('findMethodById')
            ->willReturn(null);

        return $mockCustomerFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function prepareRestCheckoutRequestAttributesTransferWithoutShipment(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function prepareRestCheckoutRequestAttributesTransferWithShipment(): RestCheckoutRequestAttributesTransfer
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
    protected function prepareCheckoutDataTransferWithShipmentMethodId(): CheckoutDataTransfer
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
    protected function prepareCheckoutDataTransferWithInvalidShipmentMethodId(): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withShipment(['idShipmentMethod' => static::SHIPMENT_METHOD_ID_INVALID])
            ->build();

        return $checkoutDataTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function prepareQuoteTransfer(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())->build();

        return $quoteTransfer;
    }
}
