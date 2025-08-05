<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SelfServicePortal\Plugin\CartPage;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Store\StoreClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Plugin\CartPage\ShipmentTypePreAddToCartPlugin;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\Widget\SspShipmentTypeServicePointSelectorWidget;
use SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester;

/**
 * @group SprykerFeatureTest
 * @group Yves
 * @group SelfServicePortal
 * @group Plugin
 * @group CartPage
 * @group ShipmentTypePreAddToCartPluginTest
 */
class ShipmentTypePreAddToCartPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_UUID = 'test-shipment-type-uuid';

    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_NAME = 'Test Shipment Type';

    /**
     * @var int
     */
    protected const TEST_SHIPMENT_TYPE_ID = 1;

    /**
     * @var \SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester
     */
    protected SelfServicePortalYvesTester $tester;

    public function testPreAddToCartExpandsItemWithShipmentTypeWhenValidUuidProvided(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageTransfer())
            ->setUuid(static::TEST_SHIPMENT_TYPE_UUID)
            ->setName(static::TEST_SHIPMENT_TYPE_NAME)
            ->setIdShipmentType(static::TEST_SHIPMENT_TYPE_ID);

        $shipmentTypeStorageCollection = (new ShipmentTypeStorageCollectionTransfer())
            ->setShipmentTypeStorages(new ArrayObject([$shipmentTypeStorageTransfer]));

        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock();
        $shipmentTypeReaderMock
            ->expects($this->once())
            ->method('getShipmentTypeStorageCollection')
            ->with(
                [static::TEST_SHIPMENT_TYPE_UUID],
                static::TEST_STORE_NAME,
            )
            ->willReturn($shipmentTypeStorageCollection);

        $this->tester->mockFactoryMethod('createShipmentTypeReader', $shipmentTypeReaderMock);
        $this->tester->mockFactoryMethod('getStoreClient', $this->createStoreClientMock());

        $itemTransfer = new ItemTransfer();
        $params = [
            SspShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_SHIPMENT_TYPE_UUID => static::TEST_SHIPMENT_TYPE_UUID,
        ];

        // Act
        $resultItemTransfer = (new ShipmentTypePreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNotNull($resultItemTransfer->getShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $resultItemTransfer->getShipmentTypeOrFail()->getUuid());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_NAME, $resultItemTransfer->getShipmentTypeOrFail()->getName());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_ID, $resultItemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
    }

    public function testPreAddToCartDoesNotExpandItemWhenParameterNotProvided(): void
    {
        // Arrange
        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock();
        $shipmentTypeReaderMock
            ->expects($this->never())
            ->method('getShipmentTypeStorageCollection');

        $this->tester->mockFactoryMethod('createShipmentTypeReader', $shipmentTypeReaderMock);
        $this->tester->mockFactoryMethod('getStoreClient', $this->createStoreClientMock());

        $itemTransfer = new ItemTransfer();
        $params = [];

        // Act
        $resultItemTransfer = (new ShipmentTypePreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getShipmentType());
    }

    public function testPreAddToCartDoesNotExpandItemWhenParameterIsEmpty(): void
    {
        // Arrange
        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock();
        $shipmentTypeReaderMock
            ->expects($this->never())
            ->method('getShipmentTypeStorageCollection');

        $this->tester->mockFactoryMethod('createShipmentTypeReader', $shipmentTypeReaderMock);
        $this->tester->mockFactoryMethod('getStoreClient', $this->createStoreClientMock());

        $itemTransfer = new ItemTransfer();
        $params = [
            SspShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_SHIPMENT_TYPE_UUID => '',
        ];

        // Act
        $resultItemTransfer = (new ShipmentTypePreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getShipmentType());
    }

    public function testPreAddToCartDoesNotExpandItemWhenShipmentTypeNotFound(): void
    {
        // Arrange
        $emptyShipmentTypeStorageCollection = new ShipmentTypeStorageCollectionTransfer();

        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock();
        $shipmentTypeReaderMock
            ->expects($this->once())
            ->method('getShipmentTypeStorageCollection')
            ->with(
                [static::TEST_SHIPMENT_TYPE_UUID],
                static::TEST_STORE_NAME,
            )
            ->willReturn($emptyShipmentTypeStorageCollection);

        $this->tester->mockFactoryMethod('createShipmentTypeReader', $shipmentTypeReaderMock);
        $this->tester->mockFactoryMethod('getStoreClient', $this->createStoreClientMock());

        $itemTransfer = new ItemTransfer();
        $params = [
            SspShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_SHIPMENT_TYPE_UUID => static::TEST_SHIPMENT_TYPE_UUID,
        ];

        // Act
        $resultItemTransfer = (new ShipmentTypePreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getShipmentType());
    }

    public function testPreAddToCartKeepsExistingShipment(): void
    {
        // Arrange
        $shipmentTypeStorageTransfer = (new ShipmentTypeStorageTransfer())
            ->setUuid(static::TEST_SHIPMENT_TYPE_UUID)
            ->setName(static::TEST_SHIPMENT_TYPE_NAME)
            ->setIdShipmentType(static::TEST_SHIPMENT_TYPE_ID);

        $shipmentTypeStorageCollection = (new ShipmentTypeStorageCollectionTransfer())
            ->setShipmentTypeStorages(new ArrayObject([$shipmentTypeStorageTransfer]));

        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock();
        $shipmentTypeReaderMock
            ->expects($this->once())
            ->method('getShipmentTypeStorageCollection')
            ->with(
                [static::TEST_SHIPMENT_TYPE_UUID],
                static::TEST_STORE_NAME,
            )
            ->willReturn($shipmentTypeStorageCollection);

        $this->tester->mockFactoryMethod('createShipmentTypeReader', $shipmentTypeReaderMock);
        $this->tester->mockFactoryMethod('getStoreClient', $this->createStoreClientMock());

        $initialShipmentTransfer = (new ShipmentTransfer())
            ->setShipmentTypeUuid(static::TEST_SHIPMENT_TYPE_UUID);
        $itemTransfer = (new ItemTransfer())->setShipment($initialShipmentTransfer);

        $params = [
            SspShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_SHIPMENT_TYPE_UUID => static::TEST_SHIPMENT_TYPE_UUID,
        ];

        // Act
        $resultItemTransfer = (new ShipmentTypePreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNotNull($resultItemTransfer->getShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $resultItemTransfer->getShipmentTypeOrFail()->getUuid());
        $this->assertSame($initialShipmentTransfer, $resultItemTransfer->getShipment());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Yves\SelfServicePortal\Service\Reader\ShipmentTypeReaderInterface
     */
    protected function createShipmentTypeReaderMock(): ShipmentTypeReaderInterface
    {
        return $this->getMockBuilder(ShipmentTypeReaderInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Store\StoreClientInterface
     */
    protected function createStoreClientMock(): StoreClientInterface
    {
        $storeClientMock = $this->getMockBuilder(StoreClientInterface::class)
            ->getMock();

        $storeClientMock
            ->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName(static::TEST_STORE_NAME));

        return $storeClientMock;
    }
}
