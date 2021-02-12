<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
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
        //Arrange
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

        //Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        //Assert
        $this->tester->assertCount(
            1,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            'Product Category Storage record count is wrong.'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEventsWithFakeIdCategory(): void
    {
        //Arrange
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

        //Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        //Assert
        $this->tester->assertCount(
            0,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            'Product Category Storage record count is wrong.'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEventsWithTwoStoreRelations(): void
    {
        //Arrange
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

        //Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        //Assert
        $this->tester->assertCount(
            2,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            'Product Category Storage record count is wrong.'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStorePublishingEvents(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($this->categoryTransfer->getIdCategory()),
        ];

        //Act
        $this->tester->getFacade()->writeCollectionByCategoryStorePublishingEvents($eventEntityTransfers);

        //Assert
        $this->tester->assertCount(
            1,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            'Product Category Storage record count is wrong.'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStorePublishingEventsWithFakeIdCategory(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId(static::FAKE_ID_CATEGORY),
        ];

        //Act
        $this->tester->getFacade()->writeCollectionByCategoryStorePublishingEvents($eventEntityTransfers);

        //Assert
        $this->tester->assertCount(
            0,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            'Product Category Storage record count is wrong.'
        );
    }

    /**
     * @return void
     */
    public function testDeleteCollectionByCategoryStoreEvents(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($this->categoryTransfer->getIdCategory()),
        ];

        $this->tester->getFacade()->writeCollectionByCategoryStorePublishingEvents($eventEntityTransfers);

        //Act
        $this->tester->getFacade()->deleteCollectionByCategoryStoreEvents($eventEntityTransfers);

        //Assert
        $this->tester->assertCount(
            0,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            'Product Category Storage record count is wrong.'
        );
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
