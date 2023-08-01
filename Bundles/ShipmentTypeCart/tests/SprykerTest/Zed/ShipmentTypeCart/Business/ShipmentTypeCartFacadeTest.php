<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeCart\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
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
 * @group ShipmentTypeCartFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentTypeCartFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_UUID = 'uuid';

    /**
     * @var \SprykerTest\Zed\ShipmentTypeCart\ShipmentTypeCartBusinessTester
     */
    protected ShipmentTypeCartBusinessTester $tester;

    /**
     * @dataProvider shipmentTypeExpanderDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string|null $expectedShipmentTypeUuid
     *
     * @return void
     */
    public function testExpandCartChangeItemsWithShipmentTypeCorrectlyExpandsItemShipmentWithShipmentTypeUuid(
        ItemTransfer $itemTransfer,
        ?string $expectedShipmentTypeUuid
    ): void {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->expandCartChangeItemsWithShipmentType($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $cartChangeTransfer->getItems());
        $itemTransfer = $cartChangeTransfer->getItems()->getIterator()->current();
        $this->assertSame($expectedShipmentTypeUuid, $itemTransfer->getShipment()->getShipmentTypeUuid());
    }

    /**
     * @dataProvider itemTransferWithoutRequiredFieldDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testExpandCartChangeItemsWithShipmentTypeThrowsExceptionWhenRequiredFieldIsNotProvided(ItemTransfer $itemTransfer): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->expandCartChangeItemsWithShipmentType($cartChangeTransfer);
    }

    /**
     * @dataProvider shipmentTypeExpanderDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string|null $expectedShipmentTypeUuid
     *
     * @return void
     */
    public function testExpandQuoteItemsWithShipmentTypeCorrectlyExpandsItemShipmentWithShipmentTypeUuid(
        ItemTransfer $itemTransfer,
        ?string $expectedShipmentTypeUuid
    ): void {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteItemsWithShipmentType($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        $itemTransfer = $quoteTransfer->getItems()->getIterator()->current();
        $this->assertSame($expectedShipmentTypeUuid, $itemTransfer->getShipment()->getShipmentTypeUuid());
    }

    /**
     * @dataProvider itemTransferWithoutRequiredFieldDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testExpandQuoteItemsWithShipmentTypeThrowsExceptionWhenRequiredFieldIsNotProvided(ItemTransfer $itemTransfer): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->expandQuoteItemsWithShipmentType($quoteTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer|string|null>>
     */
    public function shipmentTypeExpanderDataProvider(): array
    {
        return [
            'expands item shipment with shipment type uuid when its provided' => [
                (new ItemBuilder())
                    ->withShipment([ShipmentTransfer::SHIPMENT_TYPE_UUID => null])
                    ->withShipmentType([ShipmentTypeTransfer::UUID => static::TEST_SHIPMENT_TYPE_UUID])
                    ->build(),
                static::TEST_SHIPMENT_TYPE_UUID,
            ],
            'does nothing when shipment type is not provided' => [
                (new ItemBuilder([ItemTransfer::SHIPMENT_TYPE => null]))
                    ->withShipment([ShipmentTransfer::SHIPMENT_TYPE_UUID => null])
                    ->build(),
                null,
            ],
        ];
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer>>
     */
    public function itemTransferWithoutRequiredFieldDataProvider(): array
    {
        return [
            'ItemTransfer.shipment is not provided' => [
                (new ItemBuilder([ItemTransfer::SHIPMENT => null]))
                    ->withShipmentType([ShipmentTypeTransfer::UUID => static::TEST_SHIPMENT_TYPE_UUID])
                    ->build(),
            ],
            'ItemTransfer.shipmentType.uuid is not provided' => [
                (new ItemBuilder())
                    ->withShipment()
                    ->withShipmentType([ShipmentTypeTransfer::UUID => null])
                    ->build(),
            ],
        ];
    }
}
