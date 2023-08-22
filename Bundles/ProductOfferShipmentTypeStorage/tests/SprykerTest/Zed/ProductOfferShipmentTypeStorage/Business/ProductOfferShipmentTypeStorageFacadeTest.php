<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentTypeStorage\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use SprykerTest\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShipmentTypeStorage
 * @group Business
 * @group Facade
 * @group ProductOfferShipmentTypeStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferShipmentTypeStorageFacadeTest extends Unit
{
    /**
     * @uses \Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER = 'spy_product_offer_shipment_type.fk_product_offer';

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeStoreTableMap::COL_FK_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'spy_shipment_type_store.fk_shipment_type';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_1 = 'product_offer_reference_1';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_2 = 'product_offer_reference_2';

    /**
     * @var string
     */
    protected const STORE_NAME_1 = 'STORE_NAME_1';

    /**
     * @var string
     */
    protected const STORE_NAME_2 = 'STORE_NAME_2';

    /**
     * @var \SprykerTest\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageBusinessTester
     */
    protected ProductOfferShipmentTypeStorageBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->ensureProductOfferShipmentTypeStorageTableIsEmpty();
        $this->tester->ensureStoreTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsPersistsStorageDataByProductOfferShipmentTypeIds(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $productOfferShipmentTypeTransfer1 = $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $productOfferShipmentTypeTransfer2 = $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($productOfferShipmentTypeTransfer1->getIdProductOfferShipmentType()),
            (new EventEntityTransfer())->setId($productOfferShipmentTypeTransfer2->getIdProductOfferShipmentType()),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents($eventEntityTransfers);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($productOfferShipmentTypeStorageTransfer);
        $this->assertProductOfferShipmentTypeStorageTransfer(
            $productOfferShipmentTypeStorageTransfer,
            $productOfferTransfer,
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsPersistsStorageDataByFkProductOfferPassedAsAdditionalValue(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($productOfferShipmentTypeStorageTransfer);
        $this->assertProductOfferShipmentTypeStorageTransfer(
            $productOfferShipmentTypeStorageTransfer,
            $productOfferTransfer,
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferEventsPersistsStorageData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($productOfferTransfer->getIdProductOfferOrFail());

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($productOfferShipmentTypeStorageTransfer);
        $this->assertProductOfferShipmentTypeStorageTransfer(
            $productOfferShipmentTypeStorageTransfer,
            $productOfferTransfer,
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferStoreEventsPersistsStorageData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferStoreEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($productOfferShipmentTypeStorageTransfer);
        $this->assertProductOfferShipmentTypeStorageTransfer(
            $productOfferShipmentTypeStorageTransfer,
            $productOfferTransfer,
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByShipmentTypeEventsPersistsStorageData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($shipmentTypeTransfer1->getIdShipmentTypeOrFail());

        // Act
        $this->tester->getFacade()->writeCollectionByShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($productOfferShipmentTypeStorageTransfer);
        $this->assertProductOfferShipmentTypeStorageTransfer(
            $productOfferShipmentTypeStorageTransfer,
            $productOfferTransfer,
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByShipmentTypeEventsShouldAvoidEndlessLoopWhenProductOfferShipmentTypeCollectionIsEmpty(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($shipmentTypeTransfer->getIdShipmentTypeOrFail());

        // Act
        $this->tester->getFacade()->writeCollectionByShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfers = $this->tester->getProductOfferShipmentTypeStorages();
        $this->assertEmpty($productOfferShipmentTypeStorageTransfers);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByShipmentTypeStoreEventsPersistsStorageData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer2);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_SHIPMENT_TYPE => $shipmentTypeTransfer1->getIdShipmentTypeOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByShipmentTypeStoreEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($productOfferShipmentTypeStorageTransfer);
        $this->assertProductOfferShipmentTypeStorageTransfer(
            $productOfferShipmentTypeStorageTransfer,
            $productOfferTransfer,
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByShipmentTypeStoreEventsShouldAvoidEndlessLoopWhenProductOfferShipmentTypeCollectionIsEmpty(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_SHIPMENT_TYPE => $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByShipmentTypeStoreEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfers = $this->tester->getProductOfferShipmentTypeStorages();
        $this->assertEmpty($productOfferShipmentTypeStorageTransfers);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsDoesNotPersistStorageDataWhenProductConcreteIsInactive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::IS_ACTIVE => false]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSkuOrFail(),
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsDoesNotPersistStorageDataWhenProductOfferIsInactive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => false,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsDoesNotPersistStorageDataWhenProductOfferIsNotApproved(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => 'not-approved',
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsDoesNotPersistStorageDataWhenShipmentTypeIsInactive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsRemovesStorageDataWhenProductConcreteIsDeactivated(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::IS_ACTIVE => false]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSkuOrFail(),
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail())
            ->addShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());
        $this->tester->createProductOfferShipmentTypeStorage($productOfferShipmentTypeStorageTransfer, $storeTransfer->getNameOrFail());

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsRemovesStorageDataWhenProductOfferIsInactive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => false,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail())
            ->addShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());
        $this->tester->createProductOfferShipmentTypeStorage($productOfferShipmentTypeStorageTransfer, $storeTransfer->getNameOrFail());

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsRemovesStorageDataWhenProductOfferIsNotApproved(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => 'not-approved',
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail())
            ->addShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());
        $this->tester->createProductOfferShipmentTypeStorage($productOfferShipmentTypeStorageTransfer, $storeTransfer->getNameOrFail());

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsRemovesStorageDataWhenShipmentTypeIsInactive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail())
            ->addShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());
        $this->tester->createProductOfferShipmentTypeStorage($productOfferShipmentTypeStorageTransfer, $storeTransfer->getNameOrFail());

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsRemovesStorageDataWhenProductOfferStoreRelationIsRemoved(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail())
            ->addShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());
        $this->tester->createProductOfferShipmentTypeStorage($productOfferShipmentTypeStorageTransfer, $storeTransfer->getNameOrFail());

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsRemovesStorageDataWhenShipmentTypeStoreRelationIsRemoved(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => new StoreRelationTransfer(),
        ]);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);

        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail())
            ->addShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());
        $this->tester->createProductOfferShipmentTypeStorage($productOfferShipmentTypeStorageTransfer, $storeTransfer->getNameOrFail());

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductOfferShipmentTypeEventsRemovesStorageDataWhenProductOfferShipmentTypeRelationIsRemoved(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail())
            ->addShipmentTypeUuid($shipmentTypeTransfer->getUuidOrFail());
        $this->tester->createProductOfferShipmentTypeStorage($productOfferShipmentTypeStorageTransfer, $storeTransfer->getNameOrFail());

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_PRODUCT_OFFER_SHIPMENT_TYPE_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferShipmentTypeEvents([$eventEntityTransfer]);

        // Assert
        $productOfferShipmentTypeStorageTransfer = $this->tester->findProductOfferShipmentTypeStorageTransfer(
            $productOfferTransfer->getProductOfferReferenceOrFail(),
            $storeTransfer->getNameOrFail(),
        );
        $this->assertNull($productOfferShipmentTypeStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer $productOfferShipmentTypeStorageTransfer
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer1
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer2
     *
     * @return void
     */
    protected function assertProductOfferShipmentTypeStorageTransfer(
        ProductOfferShipmentTypeStorageTransfer $productOfferShipmentTypeStorageTransfer,
        ProductOfferTransfer $productOfferTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer1,
        ShipmentTypeTransfer $shipmentTypeTransfer2
    ): void {
        $this->assertSame($productOfferTransfer->getProductOfferReferenceOrFail(), $productOfferShipmentTypeStorageTransfer->getProductOfferReference());
        $this->assertCount(2, $productOfferShipmentTypeStorageTransfer->getShipmentTypeUuids());
        $this->assertContainsEquals($shipmentTypeTransfer1->getUuidOrFail(), $productOfferShipmentTypeStorageTransfer->getShipmentTypeUuids());
        $this->assertContainsEquals($shipmentTypeTransfer2->getUuidOrFail(), $productOfferShipmentTypeStorageTransfer->getShipmentTypeUuids());
    }

    /**
     * @return void
     */
    public function testGetProductOfferShipmentTypeStorageSynchronizationDataTransfersReturnsProductOfferShipmentTypeStorageDataByProductOfferIds(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1],
        );
        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1);

        $this->tester->createProductOfferShipmentTypeStorage(
            $productOfferShipmentTypeStorageTransfer,
            static::STORE_NAME_1,
        );

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
                (new FilterTransfer())->setOffset(0)->setLimit(10),
                [$productOfferTransfer->getIdProductOffer()],
            );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
        $this->assertEquals(static::STORE_NAME_1, $synchronizationDataTransfers[0]->getStore());
        $this->assertNotFalse(strpos($synchronizationDataTransfers[0]->getKey(), static::PRODUCT_OFFER_REFERENCE_1));
        $this->assertEquals($productOfferShipmentTypeStorageTransfer->toArray(), $synchronizationDataTransfers[0]->getData());
    }

    /**
     * @return void
     */
    public function testGetProductOfferShipmentTypeStorageSynchronizationDataTransfersReturnsProductOfferShipmentTypeStorageDataByOffsetAndLimit(): void
    {
        // Arrange
        $productOfferTransfer1 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1],
        );
        $this->tester->createProductOfferShipmentTypeStorage(
            (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1),
            static::STORE_NAME_1,
        );
        $productOfferTransfer2 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2],
        );
        $productOfferShipmentTypeStorageTransfer = (new ProductOfferShipmentTypeStorageTransfer())
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2);

        $this->tester->createProductOfferShipmentTypeStorage(
            $productOfferShipmentTypeStorageTransfer,
            static::STORE_NAME_2,
        );

        $this->tester->createProductOfferShipmentTypeStorage(
            (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1),
            static::STORE_NAME_2,
        );

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
                (new FilterTransfer())->setOffset(1)->setLimit(1),
                [
                    $productOfferTransfer1->getIdProductOffer(),
                    $productOfferTransfer2->getIdProductOffer(),
                ],
            );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
        $this->assertEquals(static::STORE_NAME_2, $synchronizationDataTransfers[0]->getStore());
        $this->assertNotFalse(strpos($synchronizationDataTransfers[0]->getKey(), static::PRODUCT_OFFER_REFERENCE_2));
        $this->assertEquals($productOfferShipmentTypeStorageTransfer->toArray(), $synchronizationDataTransfers[0]->getData());
    }

    /**
     * @return void
     */
    public function testGetProductOfferShipmentTypeStorageSynchronizationDataTransfersReturnsProductOfferShipmentTypeStorageDataWhenNoOffsetAndLimitIsSet(): void
    {
        $productOfferTransfer1 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1],
        );
        $this->tester->createProductOfferShipmentTypeStorage(
            (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1),
            static::STORE_NAME_1,
        );
        $productOfferTransfer2 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2],
        );

        $this->tester->createProductOfferShipmentTypeStorage(
            (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2),
            static::STORE_NAME_2,
        );

        $this->tester->createProductOfferShipmentTypeStorage(
            (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1),
            static::STORE_NAME_2,
        );

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
                new FilterTransfer(),
                [
                    $productOfferTransfer1->getIdProductOffer(),
                    $productOfferTransfer2->getIdProductOffer(),
                ],
            );

        // Assert
        $this->assertCount(3, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductOfferShipmentTypeStorageSynchronizationDataTransfersReturnsEmptyArrayWhenThereIsNoDataInTheTable(): void
    {
        $productOfferTransfer1 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1],
        );
        $productOfferTransfer2 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2],
        );

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
                (new FilterTransfer())->setOffset(0),
                [
                    $productOfferTransfer1->getIdProductOffer(),
                    $productOfferTransfer2->getIdProductOffer(),
                ],
            );

        // Assert
        $this->assertCount(0, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductOfferShipmentTypeStorageSynchronizationDataTransfersReturnsEmptyArrayWhenOffsetIsHigherThenRowsAmount(): void
    {
        $productOfferTransfer1 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1],
        );
        $this->tester->createProductOfferShipmentTypeStorage(
            (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1),
            static::STORE_NAME_1,
        );
        $productOfferTransfer2 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2],
        );

        $this->tester->createProductOfferShipmentTypeStorage(
            (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_2),
            static::STORE_NAME_2,
        );

        $this->tester->createProductOfferShipmentTypeStorage(
            (new ProductOfferShipmentTypeStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1),
            static::STORE_NAME_2,
        );

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
                (new FilterTransfer())->setOffset(3),
                [
                    $productOfferTransfer1->getIdProductOffer(),
                    $productOfferTransfer2->getIdProductOffer(),
                ],
            );

        // Assert
        $this->assertCount(0, $synchronizationDataTransfers);
    }
}
