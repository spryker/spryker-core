<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeBridge;

/**
 * Auto-generated group annotations
 *
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
    /**
     * @var \SprykerTest\Zed\ShipmentsRestApi\ShipmentsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnShipmentProvided(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareRestCheckoutRequestAttributesTransferWithShipment();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertNotNull($actualQuote->getShipment());
        $this->assertGreaterThan(0, $actualQuote->getExpenses()->count());
        $actualShipmentMethodTransfer = $actualQuote->getShipment()->getMethod();
        $this->assertSame($this->tester::SHIPMENT_METHOD['idShipmentMethod'], $actualShipmentMethodTransfer->getIdShipmentMethod());
        $this->assertSame($this->tester::SHIPMENT_METHOD['storeCurrencyPrice'], $actualShipmentMethodTransfer->getStoreCurrencyPrice());
        $this->assertSame($this->tester::SHIPMENT_METHOD['currencyIsoCode'], $actualShipmentMethodTransfer->getCurrencyIsoCode());
        $this->assertSame($this->tester::SHIPMENT_METHOD['name'], $actualShipmentMethodTransfer->getName());
        $this->assertSame($this->tester::SHIPMENT_METHOD['carrierName'], $actualShipmentMethodTransfer->getCarrierName());
        $this->assertSame($this->tester::SHIPMENT_METHOD['taxRate'], $actualShipmentMethodTransfer->getTaxRate());
        $this->assertSame($this->tester::SHIPMENT_METHOD['isActive'], $actualShipmentMethodTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnShipmentProvidedWithItemLevelShippingAddresses(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareRestCheckoutRequestAttributesTransferWithShipment();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertGreaterThan(0, $actualQuote->getExpenses()->count());
        foreach ($actualQuote->getItems() as $itemTransfer) {
            $this->assertNotNull($itemTransfer->getShipment());
            $actualShipmentMethodTransfer = $itemTransfer->getShipment()->getMethod();
            $this->assertSame($this->tester::SHIPMENT_METHOD['idShipmentMethod'], $actualShipmentMethodTransfer->getIdShipmentMethod());
            $this->assertSame($this->tester::SHIPMENT_METHOD['storeCurrencyPrice'], $actualShipmentMethodTransfer->getStoreCurrencyPrice());
            $this->assertSame($this->tester::SHIPMENT_METHOD['currencyIsoCode'], $actualShipmentMethodTransfer->getCurrencyIsoCode());
            $this->assertSame($this->tester::SHIPMENT_METHOD['name'], $actualShipmentMethodTransfer->getName());
            $this->assertSame($this->tester::SHIPMENT_METHOD['carrierName'], $actualShipmentMethodTransfer->getCarrierName());
            $this->assertSame($this->tester::SHIPMENT_METHOD['taxRate'], $actualShipmentMethodTransfer->getTaxRate());
            $this->assertSame($this->tester::SHIPMENT_METHOD['isActive'], $actualShipmentMethodTransfer->getIsActive());
        }
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnNoShipmentProvided(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareRestCheckoutRequestAttributesTransferWithoutShipment();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertNull($actualQuote->getShipment());
        $this->assertCount(0, $actualQuote->getExpenses());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnNoShipmentProvidedWithItemLevelShippingAddresses(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareRestCheckoutRequestAttributesTransferWithoutShipment();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        foreach ($actualQuote->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipment());
        }

        $this->assertCount(0, $actualQuote->getExpenses());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnShipmentNotFound(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactoryWithShipmentNotFound());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareRestCheckoutRequestAttributesTransferWithShipment();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertNull($actualQuote->getShipment());
        $this->assertCount(0, $actualQuote->getExpenses());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillMapShipmentToQuoteOnShipmentNotFoundWithItemLevelShippingAddresses(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactoryWithShipmentNotFound());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareRestCheckoutRequestAttributesTransferWithShipment();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        // Act
        $actualQuote = $shipmentRestApiFacade->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        foreach ($actualQuote->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipment());
        }

        $this->assertCount(0, $actualQuote->getExpenses());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillValidateShipmentMethodCheckoutData(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $checkoutDataTransfer = $this->tester->prepareCheckoutDataTransferWithShipmentMethodId();

        // Act
        $checkoutResponseTransfer = $shipmentRestApiFacade->validateShipmentMethodCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertSame(0, $checkoutResponseTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillValidateShipmentMethodCheckoutDataWithInvalidShipmentMethodId(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactoryWithShipmentNotFound());

        $checkoutDataTransfer = $this->tester->prepareCheckoutDataTransferWithInvalidShipmentMethodId();

        // Act
        $checkoutResponseTransfer = $shipmentRestApiFacade->validateShipmentMethodCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertGreaterThan(0, $checkoutResponseTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testShipmentsRestApiFacadeWillValidateShipmentMethodCheckoutDataWithoutShipmentMethod(): void
    {
        // Arrange
        $shipmentRestApiFacade = $this->tester->getFacade();
        $shipmentRestApiFacade->setFactory($this->getMockShipmentsRestApiFactory());

        $checkoutDataTransfer = $this->tester->prepareCheckoutDataTransferWithoutShipment();

        // Act
        $checkoutResponseTransfer = $shipmentRestApiFacade->validateShipmentMethodCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShipmentsRestApiFactory(): ShipmentsRestApiBusinessFactory
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
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShipmentFacade(): ShipmentFacade
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
                (new ShipmentMethodBuilder($this->tester::SHIPMENT_METHOD))->withPrice()->build()
            );

        $mockCustomerFacade->method('findMethodById')
            ->willReturn(
                (new ShipmentMethodBuilder($this->tester::SHIPMENT_METHOD))->build()
            );

        return $mockCustomerFacade;
    }

    /**
     * @return \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShipmentsRestApiFactoryWithShipmentNotFound(): ShipmentsRestApiBusinessFactory
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
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShipmentFacadeWithShipmentNotFound(): ShipmentFacade
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
}
