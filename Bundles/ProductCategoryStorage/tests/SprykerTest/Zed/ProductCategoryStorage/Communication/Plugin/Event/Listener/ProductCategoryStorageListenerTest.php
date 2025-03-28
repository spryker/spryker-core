<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use ReflectionClass;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductCategoryStorageReader;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryAttributeStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryUrlStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\ProductCategoryPublishStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\ProductCategoryStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductCategoryStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductCategoryStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductCategoryStorage\ProductCategoryStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductCategoryTransfer
     */
    protected static $productCategoryTransfer;

    /**
     * @var int
     */
    protected static $categoryNodeId;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $categoryTransfer2 = $this->tester->haveLocalizedCategory();
        static::$categoryNodeId = $categoryTransfer->getCategoryNode()->getIdCategoryNode();
        $store = $this->tester->getAllowedStore();
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $store->getIdStore());
        $productAbstractTransfer = $this->tester->haveProductAbstract([], true);
        static::$productCategoryTransfer = $this->tester->haveProductCategoryForCategory(
            $categoryTransfer->getIdCategory(),
            [
                'fkProductAbstract' => $productAbstractTransfer->getIdProductAbstract(),
            ],
        );
        $this->tester->haveProductCategoryForCategory(
            $categoryTransfer2->getIdCategory(),
            [
                'fkProductAbstract' => $productAbstractTransfer->getIdProductAbstract(),
            ],
        );
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->removeEntryFromProductAbstractCategoryStorageTable();
        $this->clearReaderCache();
    }

    /**
     * @return void
     */
    public function testProductCategoryPublishStorageListenerStoreData(): void
    {
        // Assign
        $productCategoryPublishStorageListener = new ProductCategoryPublishStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::$productCategoryTransfer->getFkProductAbstract()),
        ];

        // Act
        $productCategoryPublishStorageListener->handleBulk($eventTransfers, ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH);

        // Assert
        $this->assertProductCategoryDatabaseEntriesAreCorrect();
    }

    /**
     * @return void
     */
    public function testProductCategoryStorageListenerStoreData(): void
    {
        // Assign
        $productCategoryStorageListener = new ProductCategoryStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT => static::$productCategoryTransfer->getFkProductAbstract(),
            ]),
        ];

        // Act
        $productCategoryStorageListener->handleBulk($eventTransfers, ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE);

        // Assert
        $this->assertProductCategoryDatabaseEntriesAreCorrect();
    }

    /**
     * @return void
     */
    public function testCategoryNodeStorageListenerStoreData(): void
    {
        // Assign
        $categoryNodeStorageListener = new CategoryNodeStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryNodeTableMap::COL_FK_CATEGORY => static::$productCategoryTransfer->getFkCategory(),
            ]),
        ];

        // Act
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE);

        // Assert
        $this->assertProductCategoryDatabaseEntriesAreCorrect();
    }

    /**
     * @return void
     */
    public function testCategoryUrlStorageListenerStoreData(): void
    {
        // Assign
        $categoryUrlStorageListener = new CategoryUrlStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => static::$categoryNodeId,
            ])
                ->setModifiedColumns([
                    SpyUrlTableMap::COL_URL,
                ]),
        ];

        // Act
        $categoryUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductCategoryDatabaseEntriesAreCorrect();
    }

    /**
     * @return void
     */
    public function testCategoryAttributeStorageListenerStoreData(): void
    {
        // Assign
        $categoryAttributeStorageListener = new CategoryAttributeStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => static::$productCategoryTransfer->getFkCategory(),
            ])
                ->setModifiedColumns([
                    SpyCategoryAttributeTableMap::COL_NAME,
                ]),
        ];

        // Act
        $categoryAttributeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $this->assertProductCategoryDatabaseEntriesAreCorrect();
    }

    /**
     * @return void
     */
    public function testCategoryStorageListenerStoreData(): void
    {
        // Assign
        $categoryStorageListener = new CategoryStorageListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::$productCategoryTransfer->getFkCategory())
                ->setModifiedColumns([
                    SpyCategoryTableMap::COL_IS_ACTIVE,
                ]),
        ];

        // Act
        $categoryStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $this->assertProductCategoryDatabaseEntriesAreCorrect();
    }

    /**
     * @return void
     */
    protected function assertProductCategoryDatabaseEntriesAreCorrect(): void
    {
        $spyProductAbstractCategoryStorage = SpyProductAbstractCategoryStorageQuery::create()
            ->orderByIdProductAbstractCategoryStorage()
            ->findByFkProductAbstract(static::$productCategoryTransfer->getFkProductAbstract());
        $this->assertNotEmpty($spyProductAbstractCategoryStorage);
        $this->assertExpectedDatabaseEntryExists($spyProductAbstractCategoryStorage);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|array<\Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage> $databaseEntries
     *
     * @return void
     */
    protected function assertExpectedDatabaseEntryExists(
        ObjectCollection $databaseEntries
    ): void {
        foreach ($databaseEntries as $databaseEntry) {
            $productAbstractCategoryStorageTransfer = (new ProductAbstractCategoryStorageTransfer())
                ->fromArray($databaseEntry->getData());
            foreach ($productAbstractCategoryStorageTransfer->getCategories() as $category) {
                if ($category->getCategoryId() === static::$productCategoryTransfer->getFkCategory()) {
                    return;
                }
            }
        }

        $this->fail(
            sprintf(
                'Category id %d was not found in database entry for product category storage',
                static::$productCategoryTransfer->getFkCategory(),
            ),
        );
    }

    /**
     * @return void
     */
    protected function removeEntryFromProductAbstractCategoryStorageTable(): void
    {
        SpyProductAbstractCategoryStorageQuery::create()
            ->filterByFkProductAbstract(static::$productCategoryTransfer->getFkProductAbstract())->delete();
    }

    /**
     * @return void
     */
    protected function clearReaderCache(): void
    {
        $reflection = new ReflectionClass(ProductCategoryStorageReader::class);
        $property = $reflection->getProperty('categoryTree');
        $property->setAccessible(true);
        $property->setValue(null);
    }
}
