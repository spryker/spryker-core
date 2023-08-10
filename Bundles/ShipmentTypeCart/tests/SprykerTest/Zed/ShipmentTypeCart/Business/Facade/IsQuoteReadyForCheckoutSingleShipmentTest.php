<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeCart\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ShipmentTypeCart\ShipmentTypeCartBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeCart
 * @group Business
 * @group Facade
 * @group IsQuoteReadyForCheckoutSingleShipmentTest
 * Add your own group annotations below this line
 */
class IsQuoteReadyForCheckoutSingleShipmentTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_UUID = 'test-shipment-type-uuid';

    /**
     * @var \SprykerTest\Zed\ShipmentTypeCart\ShipmentTypeCartBusinessTester
     */
    protected ShipmentTypeCartBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsNoErrorWhenSelectedShipmentTypeIsValid(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withShipment($this->createShipmentBuilder($shipmentTypeTransfer))
            ->build();
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $result = $this->tester->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenSelectedShipmentTypeDoesNotMatchShipmentMethodsShipmentType(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withShipment(
                (new ShipmentBuilder([
                    ShipmentTransfer::SHIPMENT_TYPE_UUID => static::TEST_SHIPMENT_TYPE_UUID,
                ]))->withMethod([ShipmentMethodTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer]),
            )
            ->build();
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $result = $this->tester->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->tester->assertCheckoutErrorTransfer(
            $checkoutResponseTransfer->getErrors()->getIterator()->current(),
            $shipmentTypeTransfer,
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenSelectedShipmentTypeNotAvailableForStore(): void
    {
        // Arrange
        $storeTransferDe = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAt = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransferDe),
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransferAt->toArray())
            ->withShipment($this->createShipmentBuilder($shipmentTypeTransfer))
            ->build();
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $result = $this->tester->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->tester->assertCheckoutErrorTransfer(
            $checkoutResponseTransfer->getErrors()->getIterator()->current(),
            $shipmentTypeTransfer,
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenSelectedShipmentTypeIsNotActive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withShipment($this->createShipmentBuilder($shipmentTypeTransfer))
            ->build();
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $result = $this->tester->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->tester->assertCheckoutErrorTransfer(
            $checkoutResponseTransfer->getErrors()->getIterator()->current(),
            $shipmentTypeTransfer,
        );
    }

    /**
     * @dataProvider returnsNoErrorWhenExpectedDataIsNotProvidedDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testReturnsNoErrorWhenExpectedDataIsNotProvided(QuoteTransfer $quoteTransfer): void
    {
        // Arrange
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $result = $this->tester->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @dataProvider throwsExceptionWhenRequiredDataIsNotProvidedDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testThrowsExceptionWhenRequiredDataIsNotProvided(QuoteTransfer $quoteTransfer): void
    {
        // Arrange
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\QuoteTransfer>>
     */
    protected function returnsNoErrorWhenExpectedDataIsNotProvidedDataProvider(): array
    {
        return [
            'Quote.shipment.shipmentTypeUuid is not provided' => [
                (new QuoteBuilder())
                    ->withStore([StoreTransfer::NAME => static::STORE_NAME_DE])
                    ->withShipment([ShipmentTransfer::SHIPMENT_TYPE_UUID => null])
                    ->build(),
            ],
            'Quote.shipment.method is not provided' => [
                (new QuoteBuilder())
                    ->withStore([StoreTransfer::NAME => static::STORE_NAME_DE])
                    ->withShipment(
                        (new ShipmentBuilder([
                            ShipmentTransfer::SHIPMENT_TYPE_UUID => static::TEST_SHIPMENT_TYPE_UUID,
                            ShipmentTransfer::METHOD => null,
                        ])),
                    )
                    ->build(),
            ],
            'Quote.shipment.method.shipmentType is not provided' => [
                (new QuoteBuilder())
                    ->withStore([StoreTransfer::NAME => static::STORE_NAME_DE])
                    ->withShipment(
                        (new ShipmentBuilder([
                            ShipmentTransfer::SHIPMENT_TYPE_UUID => static::TEST_SHIPMENT_TYPE_UUID,
                        ]))->withMethod([ShipmentMethodTransfer::SHIPMENT_TYPE => null]),
                    )
                    ->build(),
            ],
        ];
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\QuoteTransfer>>
     */
    protected function throwsExceptionWhenRequiredDataIsNotProvidedDataProvider(): array
    {
        return [
            'Quote.store is not provided' => [
                (new QuoteBuilder([QuoteTransfer::STORE => null]))
                    ->withShipment(
                        $this->createShipmentBuilder((new ShipmentTypeTransfer())->setUuid(static::TEST_SHIPMENT_TYPE_UUID)),
                    )
                    ->build(),
            ],
            'Quote.store.name is not provided' => [
                (new QuoteBuilder())
                    ->withStore([StoreTransfer::NAME => null])
                    ->withShipment(
                        $this->createShipmentBuilder((new ShipmentTypeTransfer())->setUuid(static::TEST_SHIPMENT_TYPE_UUID)),
                    )
                    ->build(),
            ],
            'Quote.shipment.method.shipmentType.uuid is not provided' => [
                (new QuoteBuilder())
                    ->withStore([StoreTransfer::NAME => static::STORE_NAME_DE])
                    ->withShipment(
                        (new ShipmentBuilder([
                            ShipmentTransfer::SHIPMENT_TYPE_UUID => static::TEST_SHIPMENT_TYPE_UUID,
                        ]))->withMethod(
                            (new ShipmentMethodBuilder())->withShipmentType([ShipmentTypeTransfer::UUID => null]),
                        ),
                    )
                    ->build(),
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\DataBuilder\ShipmentBuilder
     */
    protected function createShipmentBuilder(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentBuilder
    {
        return (new ShipmentBuilder([
            ShipmentTransfer::SHIPMENT_TYPE_UUID => $shipmentTypeTransfer->getUuidOrFail(),
        ]))->withMethod([ShipmentMethodTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer]);
    }
}
