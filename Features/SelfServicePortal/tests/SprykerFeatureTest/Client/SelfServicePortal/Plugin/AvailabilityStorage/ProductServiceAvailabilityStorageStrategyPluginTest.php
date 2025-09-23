<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Client\SelfServicePortal\Plugin\AvailabilityStorage;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientInterface;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use SprykerFeature\Client\SelfServicePortal\Plugin\AvailabilityStorage\ProductServiceAvailabilityStorageStrategyPlugin;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Client\SelfServicePortal\SelfServicePortalClientTester;

/**
 * @group SprykerFeatureTest
 * @group Client
 * @group SelfServicePortal
 * @group Plugin
 * @group AvailabilityStorage
 * @group ProductOfferServiceAvailabilityStorageStrategyPluginTest
 */
class ProductServiceAvailabilityStorageStrategyPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SKU = 'TEST-SKU-123';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE = 'TEST-OFFER-REF-123';

    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const SERVICE_SHIPMENT_TYPE_KEY = 'in-center-service';

    /**
     * @var string
     */
    protected const REGULAR_SHIPMENT_TYPE_KEY = 'delivery';

    /**
     * @var string
     */
    protected const SERVICE_PRODUCT_CLASS_NAME = 'Service';

    /**
     * @var string
     */
    protected const REGULAR_PRODUCT_CLASS_NAME = 'regular';

    /**
     * @var \SprykerFeatureTest\Client\SelfServicePortal\SelfServicePortalClientTester
     */
    protected SelfServicePortalClientTester $tester;

    /**
     * @var \SprykerFeature\Client\SelfServicePortal\Plugin\AvailabilityStorage\ProductServiceAvailabilityStorageStrategyPlugin
     */
    protected ProductServiceAvailabilityStorageStrategyPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockDependencies();
        $factory = $this->tester->getFactory();
        $factory->setConfig($this->tester->getModuleConfig());

        $this->plugin = new ProductServiceAvailabilityStorageStrategyPlugin();
        $this->plugin->setFactory($factory);
    }

    public function testIsApplicableReturnsTrueWhenAllConditionsAreMet(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer();

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsApplicableReturnsFalseWhenIdProductConcreteIsMissing(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setIdProductConcrete(null);

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsApplicableReturnsFalseWhenSkuIsMissing(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setSku(null);

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsApplicableReturnsFalseWhenProductOfferReferenceIsSet(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE);

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsApplicableReturnsFalseWhenServiceProductClassIsMissing(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setProductClassNames([static::REGULAR_PRODUCT_CLASS_NAME]);

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsApplicableReturnsFalseWhenNoProductClassNames(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setProductClassNames([]);

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsApplicableReturnsFalseWhenNoShipmentTypes(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setShipmentTypes(new ArrayObject());

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsApplicableReturnsFalseWhenNoApplicableShipmentTypes(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setShipmentTypes(new ArrayObject([
                (new ShipmentTypeStorageTransfer())->setKey(static::REGULAR_SHIPMENT_TYPE_KEY),
            ]));

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsApplicableReturnsFalseWhenNoProductOffers(): void
    {
        // Arrange
        $this->setupMockDependenciesWithNoOffers();
        $this->plugin->setFactory($this->tester->getFactory());

        $productViewTransfer = $this->createValidProductViewTransfer();

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsProductAvailableReturnsTrueWhenOffersAreAvailable(): void
    {
        // Arrange
        $this->setupMockDependenciesWithAvailableOffers();

        $factory = $this->tester->getFactory();
        $factory->setConfig($this->tester->getModuleConfig());
        $this->plugin->setFactory($factory);

        $productViewTransfer = $this->createValidProductViewTransfer();

        // Act
        $result = $this->plugin->isProductAvailable($productViewTransfer);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsProductAvailableReturnsFalseWhenOffersAreNotAvailable(): void
    {
        // Arrange
        $this->setupMockDependenciesWithUnavailableOffers();
        $this->plugin->setFactory($this->tester->getFactory());

        $productViewTransfer = $this->createValidProductViewTransfer();

        // Act
        $result = $this->plugin->isProductAvailable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsProductAvailableReturnsFalseWhenNotApplicable(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setIdProductConcrete(null);

        // Act
        $result = $this->plugin->isProductAvailable($productViewTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsApplicableReturnsTrueWithMultipleShipmentTypesIncludingApplicable(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setShipmentTypes(new ArrayObject([
                (new ShipmentTypeStorageTransfer())->setKey(static::REGULAR_SHIPMENT_TYPE_KEY),
                (new ShipmentTypeStorageTransfer())->setKey(static::SERVICE_SHIPMENT_TYPE_KEY),
            ]));

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsApplicableReturnsTrueWithMultipleProductClassesIncludingService(): void
    {
        // Arrange
        $productViewTransfer = $this->createValidProductViewTransfer()
            ->setProductClassNames([
                static::REGULAR_PRODUCT_CLASS_NAME,
                static::SERVICE_PRODUCT_CLASS_NAME,
            ]);

        // Act
        $result = $this->plugin->isApplicable($productViewTransfer);

        // Assert
        $this->assertTrue($result);
    }

    protected function createValidProductViewTransfer(): ProductViewTransfer
    {
        return (new ProductViewTransfer())
            ->setIdProductConcrete(123)
            ->setSku(static::TEST_SKU)
            ->setProductClassNames([static::SERVICE_PRODUCT_CLASS_NAME])
            ->setShipmentTypes(new ArrayObject([
                (new ShipmentTypeStorageTransfer())->setKey(static::SERVICE_SHIPMENT_TYPE_KEY),
            ]));
    }

    protected function mockDependencies(): void
    {
        $this->mockConfig();
        $this->mockProductOfferStorageClient(true);
        $this->mockProductOfferAvailabilityStorageClient(true);
        $this->mockStoreClient();
    }

    protected function setupMockDependenciesWithNoOffers(): void
    {
        $this->mockConfig();
        $this->mockProductOfferStorageClient(false);
        $this->mockProductOfferAvailabilityStorageClient(false);
        $this->mockStoreClient();
    }

    protected function setupMockDependenciesWithAvailableOffers(): void
    {
        $this->mockConfig();
        $this->mockProductOfferStorageClient(true);
        $this->mockProductOfferAvailabilityStorageClient(true, true);
        $this->mockStoreClient();
    }

    protected function setupMockDependenciesWithUnavailableOffers(): void
    {
        $this->mockConfig();
        $this->mockProductOfferStorageClient(true);
        $this->mockProductOfferAvailabilityStorageClient(true, false);
        $this->mockStoreClient();
    }

    protected function mockConfig(): void
    {
        $this->tester->mockConfigMethod('getProductOfferServiceAvailabilityShipmentTypeKeys', [static::SERVICE_SHIPMENT_TYPE_KEY]);
        $this->tester->mockConfigMethod('getServiceProductClassName', static::SERVICE_PRODUCT_CLASS_NAME);
    }

    protected function mockProductOfferStorageClient(bool $hasOffers): void
    {
        $productOfferStorageClientMock = $this->createMock(ProductOfferStorageClientInterface::class);

        $productOfferStorageCollectionTransfer = new ProductOfferStorageCollectionTransfer();

        if ($hasOffers) {
            $productOfferStorageTransfer = new ProductOfferStorageTransfer();
            $productOfferStorageTransfer->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE);
            $productOfferStorageTransfer->setShipmentTypes(new ArrayObject([
                (new ShipmentTypeStorageTransfer())->setKey(static::SERVICE_SHIPMENT_TYPE_KEY),
            ]));
            $productOfferStorageCollectionTransfer->addProductOffer($productOfferStorageTransfer);
        }

        $productOfferStorageClientMock->method('getProductOfferStoragesBySkus')
            ->willReturn($productOfferStorageCollectionTransfer);

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $productOfferStorageClientMock,
        );
    }

    protected function mockProductOfferAvailabilityStorageClient(bool $hasOffers, bool $isAvailable = false): void
    {
        $productOfferAvailabilityStorageClientMock = $this->createMock(ProductOfferAvailabilityStorageClientInterface::class);

        $availabilityTransfers = [];
        if ($hasOffers) {
            $productOfferAvailabilityStorageTransfer = new ProductOfferAvailabilityStorageTransfer();
            $productOfferAvailabilityStorageTransfer->setIsAvailable($isAvailable);
            $availabilityTransfers[] = $productOfferAvailabilityStorageTransfer;
        }

        $productOfferAvailabilityStorageClientMock->method('getByProductOfferReferences')
            ->willReturn($availabilityTransfers);

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE,
            $productOfferAvailabilityStorageClientMock,
        );
    }

    protected function mockStoreClient(): void
    {
        $storeTransfer = new StoreTransfer();
        $storeTransfer->setName(static::TEST_STORE_NAME);

        $storeClientMock = $this->createMock(StoreClientInterface::class);
        $storeClientMock->method('getCurrentStore')
            ->willReturn($storeTransfer);

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::CLIENT_STORE,
            $storeClientMock,
        );
    }
}
