<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
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
     *
     * @var string
     */
    protected const CATEGORY_STORE_PUBLISH = 'Category.category_store.publish';

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
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_CATEGORY_NODE_ID = 'category-node-id';

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
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);
        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryStorePublishEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByCategoryStoreEventsWillWriteStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryStoreTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryStoreEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryTreeStorageCollectionWillWriteStorageData(): void
    {
        // Arrange
        $expectedCount = 1;
        $this->tester->ensureCategoryTreeStorageDatabaseTableIsEmpty();

        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);

        // Act
        $this->tester->getFacade()->writeCategoryTreeStorageCollection();

        // Assert
        $categoryTreeStorageEntities = $this->tester->findCategoryTreeStorageEntitiesByLocalizedCategoryAndStoreName(
            $categoryTransfer,
            static::STORE_NAME_DE,
        );
        $this->assertCount($expectedCount, $categoryTreeStorageEntities, sprintf('Exactly %d category tree should exist.', $expectedCount));
    }

    /**
     * @return void
     */
    public function testDeleteCategoryTreeStorageCollectionWillDeleteCategoryTreeStorageData(): void
    {
        // Arrange
        $this->tester->ensureCategoryTreeStorageDatabaseTableIsEmpty();

        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->getFacade()->writeCategoryTreeStorageCollection();

        // Act
        $this->tester->getFacade()->deleteCategoryTreeStorageCollection();

        // Assert
        $categoryTreeStorageEntityCount = SpyCategoryTreeStorageQuery::create()->count();
        $this->assertEquals(0, $categoryTreeStorageEntityCount, 'Category tree storage table should be empty.');
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByCategoryAttributeEventsWillWriteStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryAttributeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByCategoryEventsWillWriteStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByCategoryTemplateEventsWillWriteStorageData(): void
    {
        // Arrange
        $categoryTemplateTranfer = $this->tester->haveCategoryTemplate();

        $categorySeedData = [
            CategoryTransfer::CATEGORY_TEMPLATE => $categoryTemplateTranfer->toArray(),
        ];
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation($categorySeedData, [StoreTransfer::NAME => static::STORE_NAME_DE]);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTemplateTranfer->getIdCategoryTemplate());

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryTemplateEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByParentCategoryEventsWithoutParentWillWriteStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE => $categoryTransfer->getCategoryNode()->getIdCategoryNode(),
        ]);

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByParentCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByParentCategoryEventsWithOriginalParentWillWriteStorageData(): void
    {
        // Arrange
        $storeData = [StoreTransfer::NAME => static::STORE_NAME_DE];

        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], $storeData);
        $originalParentCategoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], $storeData);

        $eventEntityTransfer = (new EventEntityTransfer())
            ->setForeignKeys([
                SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE => $categoryTransfer->getCategoryNode()->getIdCategoryNode(),
            ])
            ->setOriginalValues([
                SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE => $originalParentCategoryTransfer->getCategoryNode()->getIdCategoryNode(),
            ]);

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByParentCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageWriterAsserts($categoryTransfer);
        $this->executeCategoryNodeStorageWriterAsserts($originalParentCategoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByCategoryNodeEventsWillWriteStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);

        $eventEntityTransfer = (new EventEntityTransfer())->setId(
            $categoryTransfer->getCategoryNode()->getIdCategoryNode(),
        );

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryNodeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodeStorageCollectionByCategoryNodeEventsWillNotWriteInactiveStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([
            CategoryTransfer::IS_ACTIVE => false,
        ], [StoreTransfer::NAME => static::STORE_NAME_DE]);

        $eventEntityTransfer = (new EventEntityTransfer())->setId(
            $categoryTransfer->getCategoryNode()->getIdCategoryNode(),
        );

        // Act
        $this->tester->getFacade()->writeCategoryNodeStorageCollectionByCategoryNodeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCategoryNodeStorageCollectionByCategoryEventsWillDeleteCategoryStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveCategoryNodeStorageByLocalizedCategory($categoryTransfer, static::STORE_NAME_DE);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->deleteCategoryNodeStorageCollectionByCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCategoryNodeStorageCollectionByCategoryAttributeEventsWillDeleteCategoryStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveCategoryNodeStorageByLocalizedCategory($categoryTransfer, static::STORE_NAME_DE);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->deleteCategoryNodeStorageCollectionByCategoryAttributeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCategoryNodeStorageCollectionByCategoryTemplateEventsWillDeleteCategoryStorageData(): void
    {
        // Arrange
        $categoryTemplateTranfer = $this->tester->haveCategoryTemplate();

        $categorySeedData = [
            CategoryTransfer::CATEGORY_TEMPLATE => $categoryTemplateTranfer->toArray(),
        ];

        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation($categorySeedData, [StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveCategoryNodeStorageByLocalizedCategory($categoryTransfer, static::STORE_NAME_DE);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTemplateTranfer->getIdCategoryTemplate());

        // Act
        $this->tester->getFacade()->deleteCategoryNodeStorageCollectionByCategoryTemplateEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCategoryNodeStorageCollectionByCategoryNodeEventsWillDeleteCategoryStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveCategoryNodeStorageByLocalizedCategory($categoryTransfer, static::STORE_NAME_DE);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getCategoryNode()->getIdCategoryNode());

        // Act
        $this->tester->getFacade()->deleteCategoryNodeStorageCollectionByCategoryNodeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodeStorageDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testGetCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIdsWillReturnCategoryStorageDataFilteredByIds(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);
        $categoryNodeId = $categoryTransfer->getCategoryNode()->getIdCategoryNode();
        $this->tester->haveCategoryNodeStorageByLocalizedCategory(
            $categoryTransfer,
            static::STORE_NAME_DE,
            [static::KEY_CATEGORY_NODE_ID => $categoryNodeId],
        );

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIds(0, 100, [$categoryNodeId]);

        // Assert
        $categoryNodeIds = array_map(function (SynchronizationDataTransfer $synchronizationDataTransfer) {
            return $synchronizationDataTransfer->getData()[static::KEY_CATEGORY_NODE_ID];
        }, $synchronizationDataTransfers);
        $this->assertSame([$categoryNodeId], $categoryNodeIds, 'Synchronization data should be filtered by category node IDs.');
    }

    /**
     * @return void
     */
    public function testGetCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIdsWillReturnCategoryStorageDataByLimit(): void
    {
        // Arrange
        $expectedCount = 1;
        for ($i = 0; $i < 3; $i++) {
            $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);
            $this->tester->haveCategoryNodeStorageByLocalizedCategory($categoryTransfer, static::STORE_NAME_DE);
        }

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIds(0, $expectedCount, []);

        // Assert
        $this->assertCount($expectedCount, $synchronizationDataTransfers, sprintf('Exactly %d category nodes should exist.', $expectedCount));
    }

    /**
     * @return void
     */
    public function testGetCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIdsWillReturnCategoryTreeStorageDataFilteredByIds(): void
    {
        // Arrange
        $expectedCount = 1;

        $categoryTransferDE = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_DE]);
        $categoryTreeStorageDE = $this->tester->haveCategoryTreeStorageEntityByLocalizedCategoryAndStoreName($categoryTransferDE, static::STORE_NAME_DE);

        $categoryTransferUS = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => static::STORE_NAME_AT]);
        $this->tester->haveCategoryTreeStorageEntityByLocalizedCategoryAndStoreName($categoryTransferUS, static::STORE_NAME_AT);

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIds(0, 100, [$categoryTreeStorageDE->getIdCategoryTreeStorage()]);

        // Assert
        $this->assertCount($expectedCount, $synchronizationDataTransfers, sprintf('Exactly %d category trees should be found.', $expectedCount));
    }

    /**
     * @return void
     */
    public function testGetCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIdsWillReturnCategoryTreeStorageDataByLimit(): void
    {
        // Arrange
        $expectedCount = 1;

        foreach ([static::STORE_NAME_DE, static::STORE_NAME_AT] as $storeName) {
            $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation([], [StoreTransfer::NAME => $storeName]);
            $this->tester->haveCategoryTreeStorageEntityByLocalizedCategoryAndStoreName($categoryTransfer, $storeName);
        }

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIds(0, $expectedCount, []);

        // Assert
        $this->assertCount($expectedCount, $synchronizationDataTransfers, sprintf('Exactly %d category trees should be found.', $expectedCount));
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCategoryNodeStorageWriterAsserts(CategoryTransfer $categoryTransfer): void
    {
        $categoryNodeStorageEntity = $this->tester->findCategoryNodeStorageEntityByLocalizedCategoryAndStoreName($categoryTransfer, static::STORE_NAME_DE);

        $this->assertNotNull($categoryNodeStorageEntity, 'CategoryNodeStorageEntity should exist.');

        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        $categoryLocalizedAttributesTransfer = $categoryTransfer->getLocalizedAttributes()->offsetGet(0);
        $data = $categoryNodeStorageEntity->getData();

        $this->assertSame(
            $categoryLocalizedAttributesTransfer->getName(),
            $data[static::KEY_NAME],
            'The name of the CategoryNodeStorageEntity does not equals to expected value.',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCategoryNodeStorageDeleterAsserts(CategoryTransfer $categoryTransfer): void
    {
        $categoryNodeStorageEntity = $this->tester->findCategoryNodeStorageEntityByLocalizedCategoryAndStoreName($categoryTransfer, static::STORE_NAME_DE);
        $this->assertNull($categoryNodeStorageEntity, 'CategoryNodeStorageEntity should not exist.');
    }
}
