<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypesRestApi\Plugin\CheckoutRestApi;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Generated\Shared\Transfer\RestShipmentTransfer;
use Generated\Shared\Transfer\RestShipmentTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Glue\ShipmentTypesRestApi\Plugin\CheckoutRestApi\SelectedShipmentTypesCheckoutDataResponseMapperPlugin;
use SprykerTest\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiPluginTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentTypesRestApi
 * @group Plugin
 * @group CheckoutRestApi
 * @group SelectedShipmentTypesCheckoutDataResponseMapperPluginTest
 * Add your own group annotations below this line
 */
class SelectedShipmentTypesCheckoutDataResponseMapperPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_UUID = 'shipment-type-uuid';

    /**
     * @var \SprykerTest\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiPluginTester
     */
    protected ShipmentTypesRestApiPluginTester $tester;

    /**
     * @return void
     */
    public function testMapMapsDataWhenSingleShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID);
        $itemTransfer = (new ItemTransfer())
            ->setShipmentType($shipmentTypeTransfer);

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->setShipment(new RestShipmentTransfer());

        // Act
        $restCheckoutDataResponseAttributesTransfer = (new SelectedShipmentTypesCheckoutDataResponseMapperPlugin())
            ->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                (new RestCheckoutDataTransfer())->setQuote($quoteTransfer),
                $restCheckoutRequestAttributesTransfer,
                new RestCheckoutDataResponseAttributesTransfer(),
            );

        // Assert
        $this->assertCount(1, $restCheckoutDataResponseAttributesTransfer->getSelectedShipmentTypes());
        $this->assertInstanceOf(
            RestShipmentTypeTransfer::class,
            $restCheckoutDataResponseAttributesTransfer->getSelectedShipmentTypes()->offsetGet(0),
        );
        $this->assertSame(
            $shipmentTypeTransfer->getUuidOrFail(),
            $restCheckoutDataResponseAttributesTransfer->getSelectedShipmentTypes()->offsetGet(0)->getIdOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testMapMapsUniqueRestShipmentTypeTransfersWhenSingleShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID);
        $itemTransfer1 = (new ItemTransfer())
            ->setShipmentType($shipmentTypeTransfer);

        $itemTransfer2 = (new ItemTransfer())
            ->setShipmentType($shipmentTypeTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2);

        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->setShipment(new RestShipmentTransfer());

        // Act
        $restCheckoutDataResponseAttributesTransfer = (new SelectedShipmentTypesCheckoutDataResponseMapperPlugin())
            ->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                (new RestCheckoutDataTransfer())->setQuote($quoteTransfer),
                $restCheckoutRequestAttributesTransfer,
                new RestCheckoutDataResponseAttributesTransfer(),
            );

        // Assert
        $this->assertCount(1, $restCheckoutDataResponseAttributesTransfer->getSelectedShipmentTypes());
        $this->assertInstanceOf(
            RestShipmentTypeTransfer::class,
            $restCheckoutDataResponseAttributesTransfer->getSelectedShipmentTypes()->offsetGet(0),
        );
        $this->assertSame(
            $shipmentTypeTransfer->getUuidOrFail(),
            $restCheckoutDataResponseAttributesTransfer->getSelectedShipmentTypes()->offsetGet(0)->getIdOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testMapDoesntMapDataWhenMultiShipmentRequestGiven(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID);
        $itemTransfer = (new ItemTransfer())
            ->setShipmentType($shipmentTypeTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);

        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->setShipments(new ArrayObject(new RestShipmentsTransfer()));

        // Act
        $restCheckoutDataResponseAttributesTransfer = (new SelectedShipmentTypesCheckoutDataResponseMapperPlugin())
            ->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                (new RestCheckoutDataTransfer())->setQuote($quoteTransfer),
                $restCheckoutRequestAttributesTransfer,
                new RestCheckoutDataResponseAttributesTransfer(),
            );

        // Assert
        $this->assertCount(0, $restCheckoutDataResponseAttributesTransfer->getSelectedShipmentTypes());
    }

    /**
     * @return void
     */
    public function testMapDoesntMapDataWhenItemWithoutShipmentTypeGiven(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->addItem(new ItemTransfer());

        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->setShipment(new RestShipmentTransfer());

        // Act
        $restCheckoutDataResponseAttributesTransfer = (new SelectedShipmentTypesCheckoutDataResponseMapperPlugin())
            ->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                (new RestCheckoutDataTransfer())->setQuote($quoteTransfer),
                $restCheckoutRequestAttributesTransfer,
                new RestCheckoutDataResponseAttributesTransfer(),
            );

        // Assert
        $this->assertEmpty(
            $restCheckoutDataResponseAttributesTransfer->getSelectedShipmentTypes(),
        );
    }
}
