<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentAppShipment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PaymentAppShipment\Business\Exception\ItemShipmentSetterNotFoundExpressCheckoutException;
use Spryker\Zed\PaymentAppShipment\Business\Exception\MissingExpressCheckoutShipmentMethodException;
use Spryker\Zed\PaymentAppShipment\Business\Exception\QuoteFieldNotFoundExpressCheckoutException;
use Spryker\Zed\PaymentAppShipment\Business\Exception\QuoteFieldNotIterableExpressCheckoutException;
use Spryker\Zed\PaymentAppShipment\Dependency\Facade\PaymentAppShipmentToShipmentFacadeInterface;
use Spryker\Zed\PaymentAppShipment\PaymentAppShipmentDependencyProvider;
use SprykerTest\Zed\PaymentAppShipment\PaymentAppShipmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentAppShipment
 * @group Business
 * @group Facade
 * @group Facade
 * @group PaymentAppShipmentFacadeTest
 * Add your own group annotations below this line
 */
class PaymentAppShipmentFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PaymentAppShipment\PaymentAppShipmentBusinessTester
     */
    protected PaymentAppShipmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestReturnsUpdatedQuoteIfShipmentMethodIsPresent(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setName('store-name');
        $shipmentMethodTransfer = $this->tester->haveShipmentMethodTransfer([
            ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'shipment-method-key',
            ShipmentMethodTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentFacadeMock = $this->createMock(PaymentAppShipmentToShipmentFacadeInterface::class);
        $shipmentFacadeMock->expects($this->once())->method('findShipmentMethodByKey')
            ->willReturn($shipmentMethodTransfer);
        $shipmentFacadeMock->expects($this->once())->method('expandQuoteWithShipmentGroups')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return $quoteTransfer;
            });

        $this->tester->setDependency(
            PaymentAppShipmentDependencyProvider::FACADE_SHIPMENT,
            $shipmentFacadeMock,
        );

        $paymentMethodKey = 'payment-method-key';
        $quoteTransfer = $this->tester->haveQuoteTransfer([
            QuoteTransfer::STORE => $storeTransfer,
        ], [
            PaymentTransfer::PAYMENT_SELECTION => $paymentMethodKey,
        ]);

        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([
            ExpressCheckoutPaymentRequestTransfer::QUOTE => $quoteTransfer,
        ]);

        // Act
        $expressCheckoutPaymentResponseTransfer = $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer, new ExpressCheckoutPaymentResponseTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer */
        $quoteItemTransfer = $expressCheckoutPaymentResponseTransfer->getQuote()->getItems()->offsetGet(0);
        $this->assertSame($shipmentMethodTransfer, $quoteItemTransfer->getShipment()->getMethod());
        $this->assertSame((string)$shipmentMethodTransfer->getIdShipmentMethod(), $quoteItemTransfer->getShipment()->getShipmentSelection());
        $this->assertInstanceOf(AddressTransfer::class, $quoteItemTransfer->getShipment()->getShippingAddress());
        $this->assertSame($expressCheckoutPaymentResponseTransfer->getQuote()->getCustomer()->getShippingAddress()->offsetGet(0), $quoteItemTransfer->getShipment()->getShippingAddress());
    }

    /**
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestReturnsUpdatedQuoteIfShipmentMethodIsPresentAndGetShipmentItemCollectionFieldIsSetToBundleItems(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setName('store-name');
        $shipmentMethodTransfer = $this->tester->haveShipmentMethodTransfer([
            ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'shipment-method-key',
            ShipmentMethodTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores(($storeTransfer)),
        ]);

        $shipmentFacadeMock = $this->createMock(PaymentAppShipmentToShipmentFacadeInterface::class);
        $shipmentFacadeMock->expects($this->once())->method('findShipmentMethodByKey')
            ->willReturn($shipmentMethodTransfer);
        $shipmentFacadeMock->expects($this->once())->method('expandQuoteWithShipmentGroups')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return $quoteTransfer;
            });

        $this->tester->setDependency(
            PaymentAppShipmentDependencyProvider::FACADE_SHIPMENT,
            $shipmentFacadeMock,
        );

        $this->tester->mockConfigMethod(
            'getShipmentItemCollectionFieldNames',
            [QuoteTransfer::BUNDLE_ITEMS],
        );

        $paymentMethodKey = 'payment-method-key';
        $quoteTransfer = $this->tester->haveQuoteTransfer([
            QuoteTransfer::STORE => $storeTransfer,
        ], [
            PaymentTransfer::PAYMENT_SELECTION => $paymentMethodKey,
        ]);
        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([
            ExpressCheckoutPaymentRequestTransfer::QUOTE => $quoteTransfer,
        ]);

        // Act
        $expressCheckoutPaymentResponseTransfer = $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer, new ExpressCheckoutPaymentResponseTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer */
        $quoteItemTransfer = $expressCheckoutPaymentResponseTransfer->getQuote()->getItems()->offsetGet(0);
        $quoteBundleItemTransfer = $expressCheckoutPaymentResponseTransfer->getQuote()->getBundleItems()->offsetGet(0);
        $shippingAddressTransfer = $expressCheckoutPaymentResponseTransfer->getQuote()->getCustomer()->getShippingAddress()->offsetGet(0);

        $this->assertSame($shipmentMethodTransfer, $quoteItemTransfer->getShipment()->getMethod());
        $this->assertSame((string)$shipmentMethodTransfer->getIdShipmentMethod(), $quoteItemTransfer->getShipment()->getShipmentSelection());
        $this->assertInstanceOf(AddressTransfer::class, $quoteItemTransfer->getShipment()->getShippingAddress());
        $this->assertSame($shipmentMethodTransfer, $quoteBundleItemTransfer->getShipment()->getMethod());
        $this->assertSame((string)$shipmentMethodTransfer->getIdShipmentMethod(), $quoteBundleItemTransfer->getShipment()->getShipmentSelection());
        $this->assertInstanceOf(AddressTransfer::class, $quoteBundleItemTransfer->getShipment()->getShippingAddress());
        $this->assertSame($shippingAddressTransfer, $quoteItemTransfer->getShipment()->getShippingAddress());
        $this->assertSame($shippingAddressTransfer, $quoteBundleItemTransfer->getShipment()->getShippingAddress());
    }

    /**
     * @dataProvider shipmentMethodDataProvider
     *
     * @param array<string, string> $expressCheckoutShipmentMethodsIndexedByPaymentMethod
     * @param string $paymentMethodKey
     * @param string $expectedShipmentMethodKey
     *
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestReturnsUpdatedQuoteWithCorrectShipmentMethodProvided(
        array $expressCheckoutShipmentMethodsIndexedByPaymentMethod,
        string $paymentMethodKey,
        string $expectedShipmentMethodKey
    ): void {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setName('store-name');
        $shipmentFacadeMock = $this->createMock(PaymentAppShipmentToShipmentFacadeInterface::class);
        $shipmentFacadeMock->expects($this->once())->method('findShipmentMethodByKey')
            ->willReturnCallback(function (string $shipmentMethodKey) use ($storeTransfer) {
                return $this->tester->haveShipmentMethodTransfer([
                    ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => $shipmentMethodKey,
                    ShipmentMethodTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores(($storeTransfer)),
                ]);
            });
        $shipmentFacadeMock->expects($this->once())->method('expandQuoteWithShipmentGroups')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return $quoteTransfer;
            });

        $this->tester->setDependency(
            PaymentAppShipmentDependencyProvider::FACADE_SHIPMENT,
            $shipmentFacadeMock,
        );

        $this->tester->mockConfigMethod(
            'getExpressCheckoutShipmentMethodsIndexedByPaymentMethod',
            $expressCheckoutShipmentMethodsIndexedByPaymentMethod,
        );

        $quoteTransfer = $this->tester->haveQuoteTransfer([
            QuoteTransfer::STORE => $storeTransfer,
        ], [
            PaymentTransfer::PAYMENT_SELECTION => $paymentMethodKey,
        ]);
        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([
            ExpressCheckoutPaymentRequestTransfer::QUOTE => $quoteTransfer,
        ]);

        // Act
        $expressCheckoutPaymentResponseTransfer = $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer, new ExpressCheckoutPaymentResponseTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer */
        $quoteItemTransfer = $expressCheckoutPaymentResponseTransfer->getQuote()->getItems()->offsetGet(0);
        $this->assertSame($expectedShipmentMethodKey, $quoteItemTransfer->getShipment()->getMethod()->getShipmentMethodKey());
        $this->assertInstanceOf(AddressTransfer::class, $quoteItemTransfer->getShipment()->getShippingAddress());
        $this->assertSame($expressCheckoutPaymentResponseTransfer->getQuote()->getCustomer()->getShippingAddress()->offsetGet(0), $quoteItemTransfer->getShipment()->getShippingAddress());
    }

    /**
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestThrowsExceptionIfShipmentMethodIsNotPresent(): void
    {
        // Arrange
        $shipmentFacadeMock = $this->createMock(PaymentAppShipmentToShipmentFacadeInterface::class);
        $shipmentFacadeMock->expects($this->once())->method('findShipmentMethodByKey')
            ->willReturn(null);
        $shipmentFacadeMock->expects($this->never())->method('expandQuoteWithShipmentGroups');

        $this->tester->setDependency(
            PaymentAppShipmentDependencyProvider::FACADE_SHIPMENT,
            $shipmentFacadeMock,
        );

        $quoteTransfer = $this->tester->haveQuoteTransfer([], [
            PaymentTransfer::PAYMENT_SELECTION => 'payment-method-key',
        ]);

        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([
            ExpressCheckoutPaymentRequestTransfer::QUOTE => $quoteTransfer,
        ]);

        // Assert
        $this->expectException(MissingExpressCheckoutShipmentMethodException::class);

        // Act
         $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer, new ExpressCheckoutPaymentResponseTransfer());
    }

    /**
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestThrowsExceptionIfShipmentMethodIsNotAvailableForStore(): void
    {
        // Arrange
        $shipmentFacadeMock = $this->createMock(PaymentAppShipmentToShipmentFacadeInterface::class);
        $shipmentFacadeMock->expects($this->once())->method('findShipmentMethodByKey')
            ->willReturn(
                (new ShipmentMethodTransfer())->setStoreRelation((new StoreRelationTransfer())
                        ->addStores((new StoreTransfer())->setName('store-name'))),
            );
        $shipmentFacadeMock->expects($this->never())->method('expandQuoteWithShipmentGroups');

        $this->tester->setDependency(
            PaymentAppShipmentDependencyProvider::FACADE_SHIPMENT,
            $shipmentFacadeMock,
        );

        $quoteTransfer = $this->tester->haveQuoteTransfer([
            QuoteTransfer::STORE => (new StoreTransfer())->setName('another-store-name'),
        ], [
            PaymentTransfer::PAYMENT_SELECTION => 'payment-method-key',
        ]);

        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([
            ExpressCheckoutPaymentRequestTransfer::QUOTE => $quoteTransfer,
        ]);

        // Assert
        $this->expectException(MissingExpressCheckoutShipmentMethodException::class);

        // Act
        $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer, new ExpressCheckoutPaymentResponseTransfer());
    }

    /**
     * @dataProvider invalidShipmentItemCollectionFieldNamesDataProvider
     *
     * @param string $fieldName
     * @param string $expectedException
     *
     * @return void
     */
    public function testProcessExpressCheckoutPaymentRequestThrowsExceptionIfGetShipmentItemCollectionFieldNamesConfigurationIsInvalid(
        string $fieldName,
        string $expectedException
    ): void {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setName('store-name');
        $shipmentMethodTransfer = $this->tester->haveShipmentMethodTransfer([
            ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'shipment-method-key',
            ShipmentMethodTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $shipmentFacadeMock = $this->createMock(PaymentAppShipmentToShipmentFacadeInterface::class);
        $shipmentFacadeMock->expects($this->once())->method('findShipmentMethodByKey')
            ->willReturn($shipmentMethodTransfer);
        $shipmentFacadeMock->expects($this->never())->method('expandQuoteWithShipmentGroups')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return $quoteTransfer;
            });

        $this->tester->setDependency(
            PaymentAppShipmentDependencyProvider::FACADE_SHIPMENT,
            $shipmentFacadeMock,
        );

        $this->tester->mockConfigMethod(
            'getShipmentItemCollectionFieldNames',
            [$fieldName],
        );

        $paymentMethodKey = 'payment-method-key';
        $quoteTransfer = $this->tester->haveQuoteTransfer([
            QuoteTransfer::STORE => $storeTransfer,
        ], [
            PaymentTransfer::PAYMENT_SELECTION => $paymentMethodKey,
        ]);
        $expressCheckoutPaymentRequestTransfer = $this->tester->haveExpressCheckoutPaymentRequestTransfer([
            ExpressCheckoutPaymentRequestTransfer::QUOTE => $quoteTransfer,
        ]);

        // Assert
        $this->expectException($expectedException);

        // Act
        $this->tester->getFacade()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer, new ExpressCheckoutPaymentResponseTransfer());
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function invalidShipmentItemCollectionFieldNamesDataProvider(): array
    {
        return [
            'non-existent property' => [
                'fieldName' => 'not-existent-property',
                'expectedException' => QuoteFieldNotFoundExpressCheckoutException::class,
            ],
            'non-iterable property' => [
                'fieldName' => QuoteTransfer::CUSTOMER,
                'expectedException' => QuoteFieldNotIterableExpressCheckoutException::class,
            ],
            'items without setShipment method' => [
                'fieldName' => QuoteTransfer::PAYMENTS,
                'expectedException' => ItemShipmentSetterNotFoundExpressCheckoutException::class,
            ],
        ];
    }

    /**
     * @return array<string>
     */
    public function shipmentMethodDataProvider(): array
    {
        return [
            'shipment method is provided for payment method' => [
                'expressCheckoutShipmentMethodsIndexedByPaymentMethod' => [
                    'payment-method-key' => 'shipment-method-key',
                ],
                'paymentMethodKey' => '[payment-method-key]',
                'expectedShipmentMethodKey' => 'shipment-method-key',
            ],
            'shipment method is not provided for payment method' => [
                'expressCheckoutShipmentMethodsIndexedByPaymentMethod' => [
                    'payment-method-key' => 'shipment-method-key',
                ],
                'paymentMethodKey' => '[another-payment-method-key]',
                'expectedShipmentMethodKey' => '',
            ],
        ];
    }
}
