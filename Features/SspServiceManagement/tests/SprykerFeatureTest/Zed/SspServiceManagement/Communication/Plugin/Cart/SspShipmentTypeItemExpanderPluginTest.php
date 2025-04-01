<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Cart\SspShipmentTypeItemExpanderPlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group SspShipmentTypeItemExpanderPluginTest
 */
class SspShipmentTypeItemExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_UUID = 'test-shipment-type-uuid';

    /**
     * @var int
     */
    protected const TEST_SHIPMENT_TYPE_ID = 1;

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_NAME = 'Test Shipment Type';

    /**
     * @var string
     */
    protected const DEFAULT_SHIPMENT_TYPE_UUID = 'default-shipment-type-uuid';

    /**
     * @var \SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester
     */
    protected SspServiceManagementCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandItemsExpandsItemsWithShipmentType(): void
    {
        // Arrange
        $businessFactory = $this->tester->mockFactoryMethod('createShipmentTypeReader', $this->createShipmentTypeReaderMock(
            [static::TEST_SHIPMENT_TYPE_UUID => $this->tester->createShipmentTypeTransfer()],
        ));

        $cartChangeTransfer = $this->createCartChangeTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_UUID);
        $shipmentTypeItemExpanderPlugin = new SspShipmentTypeItemExpanderPlugin();
        $shipmentTypeItemExpanderPlugin->setBusinessFactory($businessFactory);

        // Act
        $resultCartChangeTransfer = $shipmentTypeItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_ID, $itemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_NAME, $itemTransfer->getShipmentTypeOrFail()->getName());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $itemTransfer->getShipmentOrFail()->getShipmentTypeUuid());
    }

    /**
     * @skip
     *
     * @return void
     */
    public function testExpandItemsExpandsItemsWithDefaultShipmentTypeWhenNoShipmentTypeProvided(): void
    {
        // Arrange
        $defaultShipmentTypeTransfer = $this->tester->createShipmentTypeTransfer(
            static::DEFAULT_SHIPMENT_TYPE_UUID,
            2,
            'Default Shipment Type',
        );

        $businessFactory = $this->tester->mockFactoryMethod('createShipmentTypeReader', $this->createShipmentTypeReaderMock(
            [],
            $defaultShipmentTypeTransfer,
        ));

        $cartChangeTransfer = $this->createCartChangeTransferWithoutShipmentType();
        $shipmentTypeItemExpanderPlugin = new SspShipmentTypeItemExpanderPlugin();
        $shipmentTypeItemExpanderPlugin->setBusinessFactory($businessFactory);

        // Act
        $resultCartChangeTransfer = $shipmentTypeItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getShipmentType());
        $this->assertSame(static::DEFAULT_SHIPMENT_TYPE_UUID, $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertSame(2, $itemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertSame('Default Shipment Type', $itemTransfer->getShipmentTypeOrFail()->getName());
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenNoItemsProvided(): void
    {
        // Arrange
        $businessFactory = $this->tester->mockFactoryMethod('createShipmentTypeReader', $this->createShipmentTypeReaderMock());

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject());
        $cartChangeTransfer->setQuote(
            (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME)),
        );

        $shipmentTypeItemExpanderPlugin = new SspShipmentTypeItemExpanderPlugin();
        $shipmentTypeItemExpanderPlugin->setBusinessFactory($businessFactory);

        // Act
        $resultCartChangeTransfer = $shipmentTypeItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $this->assertCount(0, $resultCartChangeTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testExpandItemsExpandsBundleItemsWithShipmentType(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransfer();

        $businessFactory = $this->tester->mockFactoryMethod('createShipmentTypeReader', $this->createShipmentTypeReaderMock(
            [static::TEST_SHIPMENT_TYPE_UUID => $shipmentTypeTransfer],
        ));

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())->setShipmentType(
                (new ShipmentTypeTransfer())->setUuid(static::TEST_SHIPMENT_TYPE_UUID),
            )->setShipment(
                (new ShipmentTransfer()),
            ),
        ]);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems($itemTransfers);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME));
        $quoteTransfer->setBundleItems($itemTransfers);

        $cartChangeTransfer->setQuote($quoteTransfer);

        $shipmentTypeItemExpanderPlugin = new SspShipmentTypeItemExpanderPlugin();
        $shipmentTypeItemExpanderPlugin->setBusinessFactory($businessFactory);

        // Act
        $resultCartChangeTransfer = $shipmentTypeItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $bundleItemTransfer = $resultCartChangeTransfer->getQuoteOrFail()->getBundleItems()[0];
        $this->assertNotNull($bundleItemTransfer->getShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $bundleItemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_ID, $bundleItemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_NAME, $bundleItemTransfer->getShipmentTypeOrFail()->getName());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $bundleItemTransfer->getShipmentOrFail()->getShipmentTypeUuid());
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNotExpandWhenShipmentTypeReaderReturnsNoResults(): void
    {
        // Arrange
        $businessFactory = $this->tester->mockFactoryMethod('createShipmentTypeReader', $this->createShipmentTypeReaderMock([]));

        $cartChangeTransfer = $this->createCartChangeTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_UUID);
        $shipmentTypeItemExpanderPlugin = new SspShipmentTypeItemExpanderPlugin();

        // Act
        $resultCartChangeTransfer = $shipmentTypeItemExpanderPlugin->expandItems($cartChangeTransfer);
        $shipmentTypeItemExpanderPlugin->setBusinessFactory($businessFactory);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertNull($itemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertNull($itemTransfer->getShipmentTypeOrFail()->getName());
    }

    /**
     * @param string|null $shipmentTypeUuid
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithShipmentType(?string $shipmentTypeUuid = null): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();

        if ($shipmentTypeUuid) {
            $shipmentTypeTransfer = new ShipmentTypeTransfer();
            $shipmentTypeTransfer->setUuid($shipmentTypeUuid);
            $itemTransfer->setShipmentType($shipmentTypeTransfer);
        }

        $shipmentTransfer = new ShipmentTransfer();
        $itemTransfer->setShipment($shipmentTransfer);

        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(
            (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME))
                ->setBundleItems(new ArrayObject()),
        );

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithoutShipmentType(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();

        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(
            (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME))
                ->setBundleItems(new ArrayObject()),
        );

        return $cartChangeTransfer;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface
     */
    protected function createShipmentTypeFacadeMock(): ShipmentTypeFacadeInterface
    {
        return $this->getMockBuilder(ShipmentTypeFacadeInterface::class)
            ->getMock();
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypesByUuid
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer|null $defaultShipmentType
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SspServiceManagement\Business\Reader\ShipmentTypeReaderInterface
     */
    protected function createShipmentTypeReaderMock(
        array $shipmentTypesByUuid = [],
        ?ShipmentTypeTransfer $defaultShipmentType = null
    ): ShipmentTypeReaderInterface {
        $shipmentTypeReaderMock = $this->getMockBuilder(ShipmentTypeReaderInterface::class)
            ->getMock();

        $shipmentTypeReaderMock->method('getShipmentTypesIndexedByUuids')
            ->willReturn($shipmentTypesByUuid);

        $shipmentTypeReaderMock->method('getDefaultShipmentType')
            ->willReturn($defaultShipmentType);

        return $shipmentTypeReaderMock;
    }
}
