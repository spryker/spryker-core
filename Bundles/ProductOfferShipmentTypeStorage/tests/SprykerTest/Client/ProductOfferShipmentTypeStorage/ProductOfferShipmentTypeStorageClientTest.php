<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferShipmentTypeStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToProductOfferStorageClientInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageDependencyProvider;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductOfferShipmentTypeStorage
 * @group ProductOfferShipmentTypeStorageClientTest
 * Add your own group annotations below this line
 */
class ProductOfferShipmentTypeStorageClientTest extends Unit
{
    /**
     * @uses \Spryker\Client\ProductOfferShipmentTypeStorage\Expander\ProductOfferStorageExpander::KEY_SHIPMENT_TYPE_UUIDS
     *
     * @var string
     */
    protected const KEY_SHIPMENT_TYPE_UUIDS = 'shipment_type_uuids';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE_1 = 'test-product-offer-reference-1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE_2 = 'test-product-offer-reference-2';

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
    protected const SHIPMENT_TYPE_UUID = 'uuid1';

    /**
     * @var string
     */
    protected const FAKE_SKU_1 = 'fake_sku_1';

    /**
     * @var string
     */
    protected const FAKE_SKU_2 = 'fake_sku_2';

    /**
     * @var string
     */
    protected const FAKE_SKU_3 = 'fake_sku_3';

    /**
     * @var string
     */
    protected const FAKE_DELIVERY = 'fake_delivery';

    /**
     * @var string
     */
    protected const FAKE_PICKUP = 'fake_pickup';

    /**
     * @var int
     */
    protected const SHIPMENT_TYPE_ID = 777;

    /**
     * @var \SprykerTest\Client\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageClientTester
     */
    protected ProductOfferShipmentTypeStorageClientTester $tester;

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesThrowsAnExceptionWhenProductOfferReferenceIsEmpty(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes(new ProductOfferStorageTransfer());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesDoesntExpandWhenNoProductOfferShipmentTypeDataExists(): void
    {
        // Arrange
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertEmpty($productOfferStorageTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesDoesntExpandWhenNoProductOfferShipmentTypeDataExistsForProductOfferReference(): void
    {
        // Arrange
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);
        $this->tester->mockProductOfferShipmentTypeStorageData(
            static::TEST_PRODUCT_OFFER_REFERENCE_2,
            [static::KEY_SHIPMENT_TYPE_UUIDS => [static::SHIPMENT_TYPE_UUID]],
            static::STORE_NAME_DE,
        );

        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->mockShipmentTypeStorageData($shipmentTypeTransfer, static::STORE_NAME_DE);

        $this->tester->mockStoreClient(static::STORE_NAME_DE);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertEmpty($productOfferStorageTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesDoesntExpandWhenNoProductOfferShipmentTypeDataExistsForStore(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);
        $this->tester->mockProductOfferShipmentTypeStorageData(
            static::TEST_PRODUCT_OFFER_REFERENCE_1,
            [static::KEY_SHIPMENT_TYPE_UUIDS => [$shipmentTypeTransfer->getUuidOrFail()]],
            static::STORE_NAME_DE,
        );

        $this->tester->mockShipmentTypeStorageData(
            $shipmentTypeTransfer,
            static::STORE_NAME_DE,
        );

        $this->tester->mockStoreClient(static::STORE_NAME_AT);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertEmpty($productOfferStorageTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesDoesntExpandWhenNoShipmentTypesFoundByShipmentTypeUuids(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID)
            ->setIdShipmentType(static::SHIPMENT_TYPE_ID);

        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);
        $this->tester->mockProductOfferShipmentTypeStorageData(
            static::TEST_PRODUCT_OFFER_REFERENCE_1,
            [static::KEY_SHIPMENT_TYPE_UUIDS => [$shipmentTypeTransfer->getUuidOrFail()]],
            static::STORE_NAME_DE,
        );

        $this->tester->mockStoreClient(static::STORE_NAME_DE);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertEmpty($productOfferStorageTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesExpandsWhenProductOfferShipmentTypeDataExistsForProductOfferReferenceAndStore(): void
    {
        // Arrange
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->mockProductOfferShipmentTypeStorageData(
            static::TEST_PRODUCT_OFFER_REFERENCE_1,
            [static::KEY_SHIPMENT_TYPE_UUIDS => [$shipmentTypeTransfer->getUuidOrFail()]],
            static::STORE_NAME_DE,
        );

        $this->tester->mockShipmentTypeStorageData(
            $shipmentTypeTransfer,
            static::STORE_NAME_DE,
        );

        $this->tester->mockStoreClient(static::STORE_NAME_DE);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertCount(1, $productOfferStorageTransfer->getShipmentTypes());
        $this->assertSame($shipmentTypeTransfer->getUuidOrFail(), $productOfferStorageTransfer->getShipmentTypes()->offsetGet(0)->getUuidOrFail());
    }

    /**
     * @return void
     */
    public function testFilterUnavailableProductOfferShipmentTypesFiltersOutShipmentTypesWithoutProductOfferShipmentTypes(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock(new ProductOfferStorageCollectionTransfer()),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(0, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testFilterUnavailableProductOfferShipmentTypesFiltersOutOnlyPickupShipmentType(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer((new ProductOfferStorageTransfer())
                ->setProductConcreteSku(static::FAKE_SKU_2)
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_DELIVERY)));

        $this->tester->setDependency(
            ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock($productOfferStorageCollectionTransfer),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(1, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
        $this->assertSame(static::FAKE_DELIVERY, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->offsetGet(0)->getKey());
    }

    /**
     * @return void
     */
    public function testFilterUnavailableProductOfferShipmentTypesFiltersOutNothingWhenOneOfferContainsBothShipmentTypes(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer((new ProductOfferStorageTransfer())
                ->setProductConcreteSku(static::FAKE_SKU_2)
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_DELIVERY))
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_PICKUP)));

        $this->tester->setDependency(
            ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock($productOfferStorageCollectionTransfer),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(2, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testFilterUnavailableProductOfferShipmentTypesFiltersOutNothingWhenOffersContainsBothShipmentTypes(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer((new ProductOfferStorageTransfer())
                ->setProductConcreteSku(static::FAKE_SKU_1)
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_DELIVERY)))
            ->addProductOffer((new ProductOfferStorageTransfer())
                ->setProductConcreteSku(static::FAKE_SKU_2)
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_PICKUP)));

        $this->tester->setDependency(
            ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock($productOfferStorageCollectionTransfer),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(2, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testFilterUnavailableProductOfferShipmentTypesFiltersOutShipmentTypesWithoutRelations(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer((new ProductOfferStorageTransfer())->setProductConcreteSku(static::FAKE_SKU_1))
            ->addProductOffer((new ProductOfferStorageTransfer())->setProductConcreteSku(static::FAKE_SKU_2))
            ->addProductOffer((new ProductOfferStorageTransfer())->setProductConcreteSku(static::FAKE_SKU_3));

        $this->tester->setDependency(
            ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock($productOfferStorageCollectionTransfer),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(0, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    protected function createShipmentTypeStorageCollection(): ShipmentTypeStorageCollectionTransfer
    {
        return (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_DELIVERY))
            ->addShipmentTypeStorage((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_PICKUP));
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setSku(static::FAKE_SKU_3));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToProductOfferStorageClientInterface
     */
    protected function createProductOfferStorageClientMock(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): ProductOfferShipmentTypeStorageToProductOfferStorageClientInterface {
        $productOfferStorageClientMock = $this
            ->getMockBuilder(ProductOfferShipmentTypeStorageToProductOfferStorageClientInterface::class)
            ->getMock();

        $productOfferStorageClientMock
            ->method('getProductOfferStoragesBySkus')
            ->willReturn($productOfferStorageCollectionTransfer);

        return $productOfferStorageClientMock;
    }
}
