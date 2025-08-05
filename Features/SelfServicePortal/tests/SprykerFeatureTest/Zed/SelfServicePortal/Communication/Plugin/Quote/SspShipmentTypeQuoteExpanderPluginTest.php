<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Quote;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ShipmentTypeItemExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Quote\SspShipmentTypeQuoteExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepository;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Quote
 * @group SspShipmentTypeQuoteExpanderPluginTest
 */
class SspShipmentTypeQuoteExpanderPluginTest extends Unit
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
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandExpandsItemsWithShipmentType(): void
    {
        // Arrange
        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock(
            [static::TEST_SHIPMENT_TYPE_UUID => $this->tester->createShipmentTypeTransfer()],
        );
        $repository = new SelfServicePortalRepository();
        $productOfferShipmentTypeFacade = $this->createMock(ProductOfferShipmentTypeFacadeInterface::class);

        $shipmentTypeItemExpander = new ShipmentTypeItemExpander(
            $shipmentTypeReaderMock,
            $repository,
            $productOfferShipmentTypeFacade,
        );

        $businessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $businessFactoryMock->method('createShipmentTypeItemExpander')
            ->willReturn($shipmentTypeItemExpander);

        $quoteTransfer = $this->createQuoteTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_UUID);
        $shipmentTypeQuoteExpanderPlugin = new SspShipmentTypeQuoteExpanderPlugin();
        $shipmentTypeQuoteExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultQuoteTransfer = $shipmentTypeQuoteExpanderPlugin->expand($quoteTransfer);

        // Assert
        $itemTransfer = $resultQuoteTransfer->getItems()[0];
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
    public function testExpandExpandsItemsWithDefaultShipmentTypeWhenNoShipmentTypeProvided(): void
    {
        // Arrange
        $defaultShipmentTypeTransfer = $this->tester->createShipmentTypeTransfer(
            static::DEFAULT_SHIPMENT_TYPE_UUID,
            2,
            'Default Shipment Type',
        );

        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock(
            [],
            $defaultShipmentTypeTransfer,
        );
        $repository = new SelfServicePortalRepository();
        $productOfferShipmentTypeFacade = $this->createMock(ProductOfferShipmentTypeFacadeInterface::class);

        $shipmentTypeItemExpander = new ShipmentTypeItemExpander(
            $shipmentTypeReaderMock,
            $repository,
            $productOfferShipmentTypeFacade,
        );

        $businessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $businessFactoryMock->method('createShipmentTypeItemExpander')
            ->willReturn($shipmentTypeItemExpander);

        $quoteTransfer = $this->createQuoteTransferWithoutShipmentType();
        $shipmentTypeQuoteExpanderPlugin = new SspShipmentTypeQuoteExpanderPlugin();
        $shipmentTypeQuoteExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultQuoteTransfer = $shipmentTypeQuoteExpanderPlugin->expand($quoteTransfer);

        // Assert
        $itemTransfer = $resultQuoteTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getShipmentType());
        $this->assertSame(static::DEFAULT_SHIPMENT_TYPE_UUID, $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertSame(2, $itemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertSame('Default Shipment Type', $itemTransfer->getShipmentTypeOrFail()->getName());
    }

    public function testExpandDoesNothingWhenNoItemsProvided(): void
    {
        // Arrange
        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock([]);
        $repository = new SelfServicePortalRepository();
        $productOfferShipmentTypeFacade = $this->createMock(ProductOfferShipmentTypeFacadeInterface::class);

        $shipmentTypeItemExpander = new ShipmentTypeItemExpander(
            $shipmentTypeReaderMock,
            $repository,
            $productOfferShipmentTypeFacade,
        );

        $businessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $businessFactoryMock->method('createShipmentTypeItemExpander')
            ->willReturn($shipmentTypeItemExpander);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setItems(new ArrayObject());
        $quoteTransfer->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME));

        $shipmentTypeQuoteExpanderPlugin = new SspShipmentTypeQuoteExpanderPlugin();
        $shipmentTypeQuoteExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultQuoteTransfer = $shipmentTypeQuoteExpanderPlugin->expand($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $resultQuoteTransfer);
    }

    public function testExpandExpandsBundleItemsWithShipmentType(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_UUID);

        $bundleItemTransfer = new ItemTransfer();
        $bundleItemTransfer->setBundleItemIdentifier('test');

        $shipmentTypeTransfer = new ShipmentTypeTransfer();
        $shipmentTypeTransfer->setUuid(static::TEST_SHIPMENT_TYPE_UUID);
        $bundleItemTransfer->setShipmentType($shipmentTypeTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $bundleItemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer->setBundleItems(new ArrayObject([$bundleItemTransfer]));

        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock([
            static::TEST_SHIPMENT_TYPE_UUID => $this->tester->createShipmentTypeTransfer(),
        ]);
        $repository = new SelfServicePortalRepository();
        $productOfferShipmentTypeFacade = $this->createMock(ProductOfferShipmentTypeFacadeInterface::class);

        $shipmentTypeItemExpander = new ShipmentTypeItemExpander(
            $shipmentTypeReaderMock,
            $repository,
            $productOfferShipmentTypeFacade,
        );

        $businessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $businessFactoryMock->method('createShipmentTypeItemExpander')
            ->willReturn($shipmentTypeItemExpander);

        $shipmentTypeQuoteExpanderPlugin = new SspShipmentTypeQuoteExpanderPlugin();
        $shipmentTypeQuoteExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultQuoteTransfer = $shipmentTypeQuoteExpanderPlugin->expand($quoteTransfer);

        // Assert
        $bundleItemTransfer = $resultQuoteTransfer->getBundleItems()[0];
        $this->assertNotNull($bundleItemTransfer->getShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $bundleItemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_ID, $bundleItemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_NAME, $bundleItemTransfer->getShipmentTypeOrFail()->getName());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $bundleItemTransfer->getShipmentOrFail()->getShipmentTypeUuid());
    }

    public function testExpandDoesNotExpandWhenShipmentTypeReaderReturnsNoResults(): void
    {
        // Arrange
        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock([]);
        $repository = new SelfServicePortalRepository();
        $productOfferShipmentTypeFacade = $this->createMock(ProductOfferShipmentTypeFacadeInterface::class);

        $shipmentTypeItemExpander = new ShipmentTypeItemExpander(
            $shipmentTypeReaderMock,
            $repository,
            $productOfferShipmentTypeFacade,
        );

        $businessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $businessFactoryMock->method('createShipmentTypeItemExpander')
            ->willReturn($shipmentTypeItemExpander);

        $quoteTransfer = $this->createQuoteTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_UUID);
        $shipmentTypeQuoteExpanderPlugin = new SspShipmentTypeQuoteExpanderPlugin();
        $shipmentTypeQuoteExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultQuoteTransfer = $shipmentTypeQuoteExpanderPlugin->expand($quoteTransfer);

        // Assert
        $itemTransfer = $resultQuoteTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertNull($itemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertNull($itemTransfer->getShipmentTypeOrFail()->getName());
    }

    protected function createQuoteTransferWithShipmentType(?string $shipmentTypeUuid = null): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = new ItemTransfer();

        if ($shipmentTypeUuid) {
            $shipmentTypeTransfer = new ShipmentTypeTransfer();
            $shipmentTypeTransfer->setUuid($shipmentTypeUuid);
            $itemTransfer->setShipmentType($shipmentTypeTransfer);
        }

        $shipmentTransfer = new ShipmentTransfer();
        $itemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer->setItems(new ArrayObject([$itemTransfer]));
        $quoteTransfer->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME));
        $quoteTransfer->setBundleItems(new ArrayObject());

        return $quoteTransfer;
    }

    protected function createQuoteTransferWithoutShipmentType(): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = new ItemTransfer();

        $quoteTransfer->setItems(new ArrayObject([$itemTransfer]));
        $quoteTransfer->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME));
        $quoteTransfer->setBundleItems(new ArrayObject());

        return $quoteTransfer;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypesByUuid
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer|null $defaultShipmentType
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\Business\Reader\ShipmentTypeReaderInterface
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
