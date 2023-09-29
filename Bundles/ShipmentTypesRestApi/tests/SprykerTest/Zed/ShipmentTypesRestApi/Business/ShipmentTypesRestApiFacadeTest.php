<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypesRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerTest\Zed\ShipmentTypesRestApi\ShipmentTypesRestApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypesRestApi
 * @group Business
 * @group Facade
 * @group ShipmentTypesRestApiFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentTypesRestApiFacadeTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_SHIPMENT_METHOD_ID_1 = 1;

    /**
     * @var int
     */
    protected const TEST_SHIPMENT_METHOD_ID_2 = 2;

    /**
     * @var \SprykerTest\Zed\ShipmentTypesRestApi\ShipmentTypesRestApiBusinessTester
     */
    protected ShipmentTypesRestApiBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandQuoteItemsWithShipmentTypesShouldExpandQuoteItemsWhenShipmentMethodProvided(): void
    {
        // Arrange
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(static::TEST_SHIPMENT_METHOD_ID_1)
            ->setShipmentType($this->tester->haveShipmentType());

        $itemTransfer = (new ItemTransfer())->setShipment(
            (new ShipmentTransfer())->setMethod($shipmentMethodTransfer),
        );

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        $this->tester->mockGetAvailableMethodsByShipment([$shipmentMethodTransfer]);

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapShipmentTypeToQuoteItem($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ShipmentTypeTransfer::class, $itemTransfer->getShipmentType());
            $this->assertSame(
                $itemTransfer->getShipmentTypeOrFail()->getIdShipmentType(),
                $shipmentMethodTransfer->getShipmentTypeOrFail()->getIdShipmentType(),
            );
        }
    }

    /**
     * @return void
     */
    public function testExpandQuoteItemsWithShipmentTypesShouldExpandQuoteItemsWhenShipmentMethodProvidedAndQuoteHasMultipleItemsWithSameShipmentType(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $shipmentMethodTransfer1 = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(static::TEST_SHIPMENT_METHOD_ID_1)
            ->setShipmentType($shipmentTypeTransfer);
        $shipmentMethodTransfer2 = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(static::TEST_SHIPMENT_METHOD_ID_2)
            ->setShipmentType($shipmentTypeTransfer);

        $itemTransfer1 = (new ItemTransfer())->setShipment(
            (new ShipmentTransfer())->setMethod($shipmentMethodTransfer1),
        );
        $itemTransfer2 = (new ItemTransfer())->setShipment(
            (new ShipmentTransfer())->setMethod($shipmentMethodTransfer2),
        );
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2);

        $this->tester->mockGetAvailableMethodsByShipment([$shipmentMethodTransfer1, $shipmentMethodTransfer2]);

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapShipmentTypeToQuoteItem($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ShipmentTypeTransfer::class, $itemTransfer->getShipmentType());
            $this->assertSame(
                $itemTransfer->getShipmentTypeOrFail()->getIdShipmentType(),
                $shipmentTypeTransfer->getIdShipmentType(),
            );
        }
    }

    /**
     * @return void
     */
    public function testExpandQuoteItemsWithShipmentTypesShouldExpandQuoteItemsWhenShipmentMethodProvidedAndQuoteHasMultipleItemsWithDifferentShipmentTypes(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentMethodTransfer1 = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(static::TEST_SHIPMENT_METHOD_ID_1)
            ->setShipmentType($shipmentTypeTransfer1);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $shipmentMethodTransfer2 = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(static::TEST_SHIPMENT_METHOD_ID_2)
            ->setShipmentType($shipmentTypeTransfer2);

        $itemTransfer1 = (new ItemTransfer())->setShipment(
            (new ShipmentTransfer())->setMethod($shipmentMethodTransfer1),
        );
        $itemTransfer2 = (new ItemTransfer())->setShipment(
            (new ShipmentTransfer())->setMethod($shipmentMethodTransfer2),
        );
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2);

        $this->tester->mockGetAvailableMethodsByShipment([$shipmentMethodTransfer1, $shipmentMethodTransfer2]);

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapShipmentTypeToQuoteItem($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ShipmentTypeTransfer::class, $itemTransfer->getShipmentType());
        }
        $this->assertSame($quoteTransfer->getItems()[0]->getShipmentTypeOrFail()->getIdShipmentType(), $shipmentTypeTransfer1->getIdShipmentType());
        $this->assertSame($quoteTransfer->getItems()[1]->getShipmentTypeOrFail()->getIdShipmentType(), $shipmentTypeTransfer2->getIdShipmentType());
    }

    /**
     * @return void
     */
    public function testExpandQuoteItemsWithShipmentTypesShouldNotExpandQuoteItemsWhenShipmentMethodDoesNotProvided(): void
    {
        // Arrange
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(static::TEST_SHIPMENT_METHOD_ID_1)
            ->setShipmentType($this->tester->haveShipmentType());

        $quoteTransfer = (new QuoteTransfer())->addItem(new ItemTransfer());

        $this->tester->mockGetAvailableMethodsByShipment([$shipmentMethodTransfer]);

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapShipmentTypeToQuoteItem($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipmentType());
        }
    }

    /**
     * @return void
     */
    public function testExpandQuoteItemsWithShipmentTypesShouldNotExpandQuoteItemsWhenShipmentMethodProvidedButNotAvailable(): void
    {
        // Arrange
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(static::TEST_SHIPMENT_METHOD_ID_1)
            ->setShipmentType($this->tester->haveShipmentType());

        $itemTransfer = (new ItemTransfer())->setShipment(
            (new ShipmentTransfer())->setMethod($shipmentMethodTransfer),
        );

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        $this->tester->mockGetAvailableMethodsByShipment([]);

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapShipmentTypeToQuoteItem($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipmentType());
        }
    }
}
