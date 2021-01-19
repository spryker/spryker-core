<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryStorage
 * @group Business
 * @group Facade
 * @group CategoryStorageFacadeTest
 * Add your own group annotations below this line
 */
class CategoryStorageFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants::CATEGORY_STORE_PUBLISH,
     */
    protected const CATEGORY_STORE_PUBLISH = 'Category.category_store.publish';

    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\CategoryStorage\CategoryStorageBusinessTester
     */
    protected $tester;

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
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByCategoryStorePublishEventsWillWriteStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());
        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        $categoryLocalizedAttributesTransfer = $categoryTransfer->getLocalizedAttributes()->offsetGet(0);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryStorePublishEvents([$eventEntityTransfer]);

        // Assert
        $categoryNodeStorageEntity = SpyCategoryNodeStorageQuery::create()
            ->filterByFkCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->filterByStore($storeTransfer->getName())
            ->filterByLocale($categoryLocalizedAttributesTransfer->getLocale()->getLocaleName())
            ->findOne();

        $this->assertNotNull($categoryNodeStorageEntity, 'CategoryNodeStorageEntity should exist.');
        $data = $categoryNodeStorageEntity->getData();
        $this->assertSame(
            $categoryLocalizedAttributesTransfer->getName(),
            $data['name'],
            'The name of the CategoryNodeStorageEntity does not equals to expected value.'
        );
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByCategoryStoreEventsWillWriteStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());
        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        $categoryLocalizedAttributesTransfer = $categoryTransfer->getLocalizedAttributes()->offsetGet(0);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryStoreTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryStoreEvents([$eventEntityTransfer]);

        // Assert
        $categoryNodeStorageEntity = SpyCategoryNodeStorageQuery::create()
            ->filterByFkCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->filterByStore($storeTransfer->getName())
            ->filterByLocale($categoryLocalizedAttributesTransfer->getLocale()->getLocaleName())
            ->findOne();

        $this->assertNotNull($categoryNodeStorageEntity, 'CategoryNodeStorageEntity should exist.');
        $data = $categoryNodeStorageEntity->getData();
        $this->assertSame(
            $categoryLocalizedAttributesTransfer->getName(),
            $data['name'],
            'The name of the CategoryNodeStorageEntity does not equals to expected value.'
        );
    }

    /**
     * @return void
     */
    public function testWriteCategoryTreeStorageCollectionWillWriteStorageData(): void
    {
        // Arrange
        $this->tester->ensureCategoryTreeStorageDatabaseTableIsEmpty();

        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        // Act
        $this->tester->getFacade()->writeCategoryTreeStorageCollection();

        // Assert
        $categoryTreeStorageEntities = SpyCategoryTreeStorageQuery::create()
            ->filterByStore($storeTransfer->getName())
            ->filterByLocale($categoryTransfer->getLocalizedAttributes()->offsetGet(0)->getLocale()->getLocaleName())
            ->find();
        $this->assertCount(1, $categoryTreeStorageEntities, 'Exactly 1 category tree should exist.');
    }

    /**
     * @return void
     */
    public function testDeleteCategoryTreeStorageCollectionWillDeleteCategoryTreeStorageData(): void
    {
        // Arrange
        $this->tester->ensureCategoryTreeStorageDatabaseTableIsEmpty();

        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());
        $this->tester->getFacade()->writeCategoryTreeStorageCollection();

        // Act
        $this->tester->getFacade()->deleteCategoryTreeStorageCollection();

        // Assert
        $categoryTreeStorageEntityCount = SpyCategoryTreeStorageQuery::create()->count();
        $this->assertEquals(0, $categoryTreeStorageEntityCount, 'Category tree storage table should be empty.');
    }
}
