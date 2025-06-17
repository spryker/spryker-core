<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ShipmentTypeItemExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\SspShipmentTypeItemExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepository;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
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
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandItemsExpandsItemsWithShipmentType(): void
    {
        // Arrange
        $cartChangeTransfer = $this->createCartChangeTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_UUID);
        $shipmentTypeItemExpanderPlugin = $this->createPluginWithMockFactory([
            static::TEST_SHIPMENT_TYPE_UUID => $this->tester->createShipmentTypeTransfer(),
        ]);

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

        $cartChangeTransfer = $this->createCartChangeTransferWithoutShipmentType();
        $shipmentTypeItemExpanderPlugin = $this->createPluginWithMockFactory([], $defaultShipmentTypeTransfer);

        // Act
        $resultCartChangeTransfer = $shipmentTypeItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $this->assertShipmentTypeItem($resultCartChangeTransfer->getItems()[0], static::DEFAULT_SHIPMENT_TYPE_UUID, 2, 'Default Shipment Type');
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenNoItemsProvided(): void
    {
        // Arrange
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote((new QuoteTransfer())->setStore(new StoreTransfer()));
        $cartChangeTransfer->setItems(new ArrayObject());

        $shipmentTypeItemExpanderPlugin = $this->createPluginWithMockFactory([]);

        // Act
        $resultCartChangeTransfer = $shipmentTypeItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $this->assertSame($cartChangeTransfer, $resultCartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testExpandItemsExpandsBundleItemsWithShipmentType(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->createShipmentTypeTransfer();

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
        $quoteTransfer->setItems($itemTransfers); // Also setting items on the quote
        $quoteTransfer->setBundleItems($itemTransfers);

        $cartChangeTransfer->setQuote($quoteTransfer);

        $shipmentTypeItemExpanderPlugin = $this->createPluginWithMockFactory([
            static::TEST_SHIPMENT_TYPE_UUID => $shipmentTypeTransfer,
        ]);

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
        $cartChangeTransfer = $this->createCartChangeTransferWithShipmentType(static::TEST_SHIPMENT_TYPE_UUID);
        $shipmentTypeItemExpanderPlugin = $this->createPluginWithMockFactory([]);

        // Act
        $resultCartChangeTransfer = $shipmentTypeItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getShipmentType());
        $this->assertSame(static::TEST_SHIPMENT_TYPE_UUID, $itemTransfer->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertNull($itemTransfer->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertNull($itemTransfer->getShipmentTypeOrFail()->getName());
    }

    /**
     * @return void
     */
    public function testExpandItemsExpandsProductItemsWithDefaultShipmentTypeWhenNoShipmentTypeProvided(): void
    {
        // Arrange
        $defaultShipmentType = (new ShipmentTypeTransfer())
            ->setUuid(static::DEFAULT_SHIPMENT_TYPE_UUID)
            ->setIdShipmentType(2)
            ->setName('Default Shipment Type');

        $itemTransfer = (new ItemTransfer())
            ->setId(123)
            ->setSku('test-product-sku')
            ->setShipment(new ShipmentTransfer());

        $items = new ArrayObject([$itemTransfer]);
        $quoteTransfer = (new QuoteTransfer())
            ->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME))
            ->setItems($items)
            ->setBundleItems(new ArrayObject());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setItems($items)
            ->setQuote($quoteTransfer);

        $shipmentTypeReader = $this->createMock(ShipmentTypeReaderInterface::class);
        $shipmentTypeReader->method('getDefaultShipmentType')
            ->with(static::TEST_STORE_NAME)
            ->willReturn($defaultShipmentType);

        $repository = $this->createMock(SelfServicePortalRepositoryInterface::class);
        $repository->method('getProductIdsWithShipmentType')
            ->with([$itemTransfer->getId()], $defaultShipmentType->getNameOrFail())
            ->willReturn([$itemTransfer->getId()]);

        $shipmentTypeItemExpander = $this->getMockBuilder(ShipmentTypeItemExpander::class)
            ->setConstructorArgs([
                $shipmentTypeReader,
                $repository,
                $this->createMock(ProductOfferShipmentTypeFacadeInterface::class),
            ])
            ->onlyMethods([])
            ->getMock();

        $businessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $businessFactoryMock->method('createShipmentTypeItemExpander')
            ->willReturn($shipmentTypeItemExpander);

        $plugin = new SspShipmentTypeItemExpanderPlugin();
        $plugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultCartChangeTransfer = $plugin->expandItems($cartChangeTransfer);

        // Assert
        $resultItem = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($resultItem->getShipmentType(), 'Product item should have shipment type set');
        $this->assertSame($defaultShipmentType->getUuidOrFail(), $resultItem->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertSame($defaultShipmentType->getIdShipmentType(), $resultItem->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertSame($defaultShipmentType->getName(), $resultItem->getShipmentTypeOrFail()->getName());
        $this->assertSame(
            $defaultShipmentType->getUuidOrFail(),
            $resultItem->getShipmentOrFail()->getShipmentTypeUuid(),
            'Shipment type UUID should be set on the shipment transfer as well',
        );
    }

    /**
     * @return void
     */
    public function testExpandItemsExpandsProductOfferItemsWithDefaultShipmentTypeWhenNoShipmentTypeProvided(): void
    {
        // Arrange
        $defaultShipmentType = (new ShipmentTypeTransfer())
            ->setUuid(static::DEFAULT_SHIPMENT_TYPE_UUID)
            ->setIdShipmentType(2)
            ->setName('Default Shipment Type');

        $productOfferReference = 'test-product-offer';
        $itemTransfer = (new ItemTransfer())
            ->setId(456)
            ->setProductOfferReference($productOfferReference)
            ->setShipment(new ShipmentTransfer());

        $items = new ArrayObject([$itemTransfer]);
        $quoteTransfer = (new QuoteTransfer())
            ->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME))
            ->setItems($items)
            ->setBundleItems(new ArrayObject());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setItems($items)
            ->setQuote($quoteTransfer);

        $shipmentTypeReader = $this->createMock(ShipmentTypeReaderInterface::class);
        $shipmentTypeReader->method('getDefaultShipmentType')
            ->with(static::TEST_STORE_NAME)
            ->willReturn($defaultShipmentType);

        $productOfferFacade = $this->createMock(ProductOfferShipmentTypeFacadeInterface::class);

        $emptyCollection = new ProductOfferShipmentTypeCollectionTransfer();
        $emptyCollection->setProductOfferShipmentTypes(new ArrayObject()); // Empty array object

        $productOfferFacade->expects($this->once())
            ->method('getProductOfferShipmentTypeCollection')
            ->willReturn($emptyCollection);

        $modifiedExpander = $this->getMockBuilder(ShipmentTypeItemExpander::class)
            ->setConstructorArgs([
                $shipmentTypeReader,
                $this->createMock(SelfServicePortalRepositoryInterface::class),
                $productOfferFacade,
            ])
            ->onlyMethods(['extractProductOfferReferencesWithDefaultShipmentType'])
            ->getMock();

        $modifiedExpander->method('extractProductOfferReferencesWithDefaultShipmentType')
            ->willReturn([$productOfferReference]);

        $businessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $businessFactoryMock->method('createShipmentTypeItemExpander')
            ->willReturn($modifiedExpander);

        $plugin = new SspShipmentTypeItemExpanderPlugin();
        $plugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultCartChangeTransfer = $plugin->expandItems($cartChangeTransfer);

        // Assert
        $resultItem = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($resultItem->getShipmentType(), 'Product offer item should have shipment type set');
        $this->assertSame($defaultShipmentType->getUuidOrFail(), $resultItem->getShipmentTypeOrFail()->getUuidOrFail());
        $this->assertSame($defaultShipmentType->getIdShipmentType(), $resultItem->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertSame($defaultShipmentType->getName(), $resultItem->getShipmentTypeOrFail()->getName());
        $this->assertSame(
            $defaultShipmentType->getUuidOrFail(),
            $resultItem->getShipmentOrFail()->getShipmentTypeUuid(),
            'Shipment type UUID should be set on the shipment transfer as well',
        );
    }

    /**
     * @param string|null $shipmentTypeUuid
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithShipmentType(?string $shipmentTypeUuid = null): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $itemTransfer = $this->createItemTransfer($shipmentTypeUuid);

        $items = new ArrayObject([$itemTransfer]);
        $cartChangeTransfer->setItems($items);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME));
        $quoteTransfer->setItems($items);
        $quoteTransfer->setBundleItems(new ArrayObject());

        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithoutShipmentType(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $itemTransfer = $this->createItemTransfer();

        $items = new ArrayObject([$itemTransfer]);
        $cartChangeTransfer->setItems($items);
        $cartChangeTransfer->setQuote(
            (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME))
                ->setBundleItems(new ArrayObject())
                ->setItems($items),
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

    /**
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypesByUuid
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer|null $defaultShipmentType
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface|null $repository
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface|null $productOfferShipmentTypeFacade
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory
     */
    protected function createMockBusinessFactory(
        array $shipmentTypesByUuid = [],
        ?ShipmentTypeTransfer $defaultShipmentType = null,
        ?SelfServicePortalRepositoryInterface $repository = null,
        ?ProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade = null
    ): SelfServicePortalBusinessFactory {
        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock($shipmentTypesByUuid, $defaultShipmentType);
        $repository = $repository ?? new SelfServicePortalRepository();
        $productOfferShipmentTypeFacade = $productOfferShipmentTypeFacade ?? $this->createMock(ProductOfferShipmentTypeFacadeInterface::class);

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

        return $businessFactoryMock;
    }

    /**
     * @param string|null $shipmentTypeUuid
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer(?string $shipmentTypeUuid = null): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setId(mt_rand(1, 1000)); // Set a random ID to avoid null value exceptions

        if ($shipmentTypeUuid) {
            $shipmentTypeTransfer = new ShipmentTypeTransfer();
            $shipmentTypeTransfer->setUuid($shipmentTypeUuid);
            $itemTransfer->setShipmentType($shipmentTypeTransfer);

            $shipmentTransfer = new ShipmentTransfer();
            $shipmentTransfer->setShipmentTypeUuid($shipmentTypeUuid); // Set UUID on shipment as well
            $itemTransfer->setShipment($shipmentTransfer);
        } else {
            $shipmentTransfer = new ShipmentTransfer();
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $itemTransfer;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypesByUuid
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer|null $defaultShipmentType
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface|null $repository
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface|null $productOfferShipmentTypeFacade
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\SspShipmentTypeItemExpanderPlugin
     */
    protected function createPluginWithMockFactory(
        array $shipmentTypesByUuid = [],
        ?ShipmentTypeTransfer $defaultShipmentType = null,
        ?SelfServicePortalRepositoryInterface $repository = null,
        ?ProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade = null
    ): SspShipmentTypeItemExpanderPlugin {
        $businessFactoryMock = $this->createMockBusinessFactory(
            $shipmentTypesByUuid,
            $defaultShipmentType,
            $repository,
            $productOfferShipmentTypeFacade,
        );

        $shipmentTypeItemExpanderPlugin = new SspShipmentTypeItemExpanderPlugin();
        $shipmentTypeItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        return $shipmentTypeItemExpanderPlugin;
    }
}
