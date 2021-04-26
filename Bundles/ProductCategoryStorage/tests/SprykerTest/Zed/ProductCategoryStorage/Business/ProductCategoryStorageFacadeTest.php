<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use ReflectionClass;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductCategoryStorageReader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryStorage
 * @group Business
 * @group Facade
 * @group ProductCategoryStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductCategoryStorageFacadeTest extends Unit
{
    protected const STORE_DE = 'DE';
    protected const STORE_AT = 'AT';

    protected const FAKE_ID_CATEGORY = 6666;
    protected const FAKE_ID_CATEGORY_NODE = 8888;
    protected const FAKE_ID_PRODUCT_ABSTRACT = 5555;

    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    protected const ASSET_MESSAGE_COUNT_IS_WRONG = 'Product Category Storage record count is wrong.';

    /**
     * @var \SprykerTest\Zed\ProductCategoryStorage\ProductCategoryStorageBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CategoryTransfer
     */
    protected $categoryTransfer;

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

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_DE]);

        $this->categoryTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $this->tester->getRootCategoryNode()->toArray(),
        ]);

        $this->tester->haveCategoryStoreRelation(
            $this->categoryTransfer->getIdCategory(),
            $storeTransfer->getIdStore()
        );
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanStaticProperty();
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEvents(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryStoreTableMap::COL_FK_CATEGORY => $this->categoryTransfer->getIdCategory(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            1,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEventsWithFakeIdCategory(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryStoreTableMap::COL_FK_CATEGORY => static::FAKE_ID_CATEGORY,
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            0,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEventsWithTwoStoreRelations(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_AT]);

        $this->tester->haveCategoryStoreRelation(
            $this->categoryTransfer->getIdCategory(),
            $storeTransfer->getIdStore()
        );

        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryStoreTableMap::COL_FK_CATEGORY => $this->categoryTransfer->getIdCategory(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            2,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStorePublishingEvents(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($this->categoryTransfer->getIdCategory()),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryStorePublishingEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            1,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStorePublishingEventsWithFakeIdCategory(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId(static::FAKE_ID_CATEGORY),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryStorePublishingEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            0,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testDeleteCollectionByCategoryStoreEvents(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($this->categoryTransfer->getIdCategory()),
        ];

        $this->tester->getFacade()->writeCollectionByCategoryStorePublishingEvents($eventEntityTransfers);

        // Act
        $this->tester->getFacade()->deleteCollectionByCategoryStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            0,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryAttributeEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $this->categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryAttributeEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryAttributeEventsWithFakeIdCategoryWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => static::FAKE_ID_CATEGORY,
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryAttributeEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryAttributeNameEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())
            ->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $this->categoryTransfer->getIdCategory(),
            ])
            ->setModifiedColumns([
                SpyCategoryAttributeTableMap::COL_NAME,
            ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryAttributeNameEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryAttributeNameEventsWithoutModifiedNameColumnWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;

        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $this->categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryAttributeNameEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryNodeEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryNodeTableMap::COL_FK_CATEGORY => $this->categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryNodeEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryNodeEventsWithFakeIdCategoryWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryNodeTableMap::COL_FK_CATEGORY => static::FAKE_ID_CATEGORY,
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryNodeEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId($this->categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryEventsWithFakeIdCategoryWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId(static::FAKE_ID_CATEGORY);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryIsActiveAndCategoryKeyEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())
            ->setId($this->categoryTransfer->getIdCategory())
            ->setModifiedColumns([
                SpyCategoryTableMap::COL_IS_ACTIVE,
                SpyCategoryTableMap::COL_CATEGORY_KEY,
            ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryIsActiveAndCategoryKeyEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryIsActiveAndCategoryKeyEventsWithoutModifiedColumnsWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId($this->categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryIsActiveAndCategoryKeyEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryUrlEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => $this->categoryTransfer->getCategoryNode()->getIdCategoryNode(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryUrlEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryUrlEventsWithFakeIdCategoryNodeWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => static::FAKE_ID_CATEGORY_NODE,
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryUrlEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryUrlEventsWithEmptyCategoryNodeWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryUrlEvents([(new EventEntityTransfer())]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryUrlAndResourceCategorynodeEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())
            ->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => $this->categoryTransfer->getCategoryNode()->getIdCategoryNode(),
            ])
            ->setModifiedColumns([
                SpyUrlTableMap::COL_URL,
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE,
            ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryUrlAndResourceCategorynodeEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryUrlAndResourceCategorynodeEventsWithoutModifiedColumnsWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => $this->categoryTransfer->getCategoryNode()->getIdCategoryNode(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryUrlAndResourceCategorynodeEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductCategoryPublishingEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId($productConcreteTransfer->getFkProductAbstract());

        // Act
        $this->tester->getFacade()->writeCollectionByProductCategoryPublishingEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductCategoryPublishingEventsWithFakeIdProductAbstractWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId(static::FAKE_ID_PRODUCT_ABSTRACT);

        // Act
        $this->tester->getFacade()->writeCollectionByProductCategoryPublishingEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductCategoryEventsWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
        ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductCategoryEventsWithFakeIdProductAbstractWillNotWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 0;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfer = (new EventEntityTransfer())->setId(static::FAKE_ID_PRODUCT_ABSTRACT);

        // Act
        $this->tester->getFacade()->writeCollectionByProductCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->tester->assertCount(
            $expectedCount,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG
        );
    }

    /**
     * @return void
     */
    public function testFindProductAbstractCategoryStorageSynchronizationDataTransfersByProductAbstractIdsWillReturnDataFilteredByIds(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $idProductAbstract = $productConcreteTransfer->getFkProductAbstract();

        $this->tester->haveProductAbstractCategoryStorageEntity(
            $productConcreteTransfer,
            static::STORE_DE,
            [
                static::KEY_ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
            ]
        );

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->findProductAbstractCategoryStorageSynchronizationDataTransfersByProductAbstractIds(0, 100, [$idProductAbstract]);

        // Assert
        $productAbstractIds = array_map(function (SynchronizationDataTransfer $synchronizationDataTransfer) {
            return $synchronizationDataTransfer->getData()[static::KEY_ID_PRODUCT_ABSTRACT];
        }, $synchronizationDataTransfers);

        $this->assertSame([$idProductAbstract], $productAbstractIds, 'Synchronization data should be filtered by product abstract IDs.');
    }

    /**
     * @return void
     */
    public function testFindProductAbstractCategoryStorageSynchronizationDataTransfersByProductAbstractIdsWillReturnDataByLimit(): void
    {
        // Arrange
        $expectedCount = 1;

        for ($i = 0; $i < 3; $i++) {
            $productConcreteTransfer = $this->tester->haveFullProduct();
            $this->tester->assignProductToCategory(
                $this->categoryTransfer->getIdCategory(),
                $productConcreteTransfer->getFkProductAbstract()
            );

            $this->tester->haveProductAbstractCategoryStorageEntity(
                $productConcreteTransfer,
                static::STORE_DE
            );
        }

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->findProductAbstractCategoryStorageSynchronizationDataTransfersByProductAbstractIds(0, $expectedCount, []);

        // Assert
        $this->assertCount($expectedCount, $synchronizationDataTransfers, sprintf('Exactly %d product abstract categories should exist.', $expectedCount));
    }

    /**
     * @return void
     */
    public function testFindProductCategoryTransfersByFilterWillReturnProductCategoryData(): void
    {
        // Arrange
        $expectedCount = 1;
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $filterTransfer = (new FilterTransfer())
            ->setOffset(0)
            ->setLimit($expectedCount);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()->findProductCategoryTransfersByFilter($filterTransfer);

        // Assert
        $this->assertCount($expectedCount, $productCategoryTransfers, sprintf('Exactly %d product categories should exist.', $expectedCount));
    }

    /**
     * @return void
     */
    protected function cleanStaticProperty(): void
    {
        $reflectedClass = new ReflectionClass(ProductCategoryStorageReader::class);
        $property = $reflectedClass->getProperty('categoryTree');
        $property->setAccessible(true);
        $property->setValue(null);
    }
}
