<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryPageSearch
 * @group Business
 * @group Facade
 * @group CategoryPageSearchFacadeTest
 * Add your own group annotations below this line
 */
class CategoryPageSearchFacadeTest extends Unit
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
    protected const KEY_SEARCH_RESULT_DATA = 'search-result-data';

    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_CATEGORY_NODE_ID = 'category-node-id';

    /**
     * @var \SprykerTest\Zed\CategoryPageSearch\CategoryPageSearchBusinessTester
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
    public function testWriteCategoryNodePageSearchCollectionByCategoryStorePublishEventsWriteSearchData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();
        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->writeCategoryNodePageSearchCollectionByCategoryStorePublishEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodePageSearchCollectionByCategoryStoreEventsWillWriteSearchData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryStoreTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeCategoryNodePageSearchCollectionByCategoryStoreEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodePageSearchCollectionByCategoryAttributeEventsWillWriteSearchData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();
        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeCategoryNodePageSearchCollectionByCategoryAttributeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodePageSearchCollectionByCategoryEventsWillWriteSearchData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();
        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->writeCategoryNodePageSearchCollectionByCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodePageSearchCollectionByCategoryTemplateEventsWillWriteSearchData(): void
    {
        // Arrange
        $categoryTemplateTranfer = $this->tester->haveCategoryTemplate();

        $categorySeedData = [
            CategoryTransfer::CATEGORY_TEMPLATE => $categoryTemplateTranfer->toArray(),
        ];
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation($categorySeedData);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTemplateTranfer->getIdCategoryTemplate());

        // Act
        $this->tester->getFacade()->writeCategoryNodePageSearchCollectionByCategoryTemplateEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodePageSearchCollectionByCategoryNodeEventsWillWriteSearchData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();
        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getCategoryNode()->getIdCategoryNode());

        // Act
        $this->tester->getFacade()->writeCategoryNodePageSearchCollectionByCategoryNodeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchWriterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCategoryNodePageSearchCollectionByCategoryAttributeEventsWillDeleteSearchData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();
        $this->tester->haveCategoryNodePageSearchByLocalizedCategory($categoryTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->deleteCategoryNodePageSearchCollectionByCategoryAttributeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCategoryNodePageSearchCollectionByCategoryEventsWillDeleteSearchData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();
        $this->tester->haveCategoryNodePageSearchByLocalizedCategory($categoryTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->deleteCategoryNodePageSearchCollectionByCategoryEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCategoryNodePageSearchCollectionByCategoryTemplateEventsWillDeleteSearchData(): void
    {
        // Arrange
        $categoryTemplateTranfer = $this->tester->haveCategoryTemplate();

        $categorySeedData = [
            CategoryTransfer::CATEGORY_TEMPLATE => $categoryTemplateTranfer->toArray(),
        ];
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation($categorySeedData);
        $this->tester->haveCategoryNodePageSearchByLocalizedCategory($categoryTransfer);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTemplateTranfer->getIdCategoryTemplate());

        // Act
        $this->tester->getFacade()->deleteCategoryNodePageSearchCollectionByCategoryTemplateEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCategoryNodePageSearchCollectionByCategoryNodeEventsWillDeleteSearchData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();
        $this->tester->haveCategoryNodePageSearchByLocalizedCategory($categoryTransfer);
        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getCategoryNode()->getIdCategoryNode());

        // Act
        $this->tester->getFacade()->deleteCategoryNodePageSearchCollectionByCategoryNodeEvents([$eventEntityTransfer]);

        // Assert
        $this->executeCategoryNodePageSearchDeleterAsserts($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testGetSynchronizationDataTransfersByCategoryNodeIdsWillReturnDataFilteredByIds(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();

        $categoryNodeId = $categoryTransfer->getCategoryNode()->getIdCategoryNode();
        $this->tester->haveCategoryNodePageSearchByLocalizedCategory($categoryTransfer, [
            static::KEY_CATEGORY_NODE_ID => $categoryNodeId,
        ]);

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getSynchronizationDataTransfersByCategoryNodeIds(0, 100, [$categoryNodeId]);

        // Assert
        $categoryNodeIds = array_map(function (SynchronizationDataTransfer $synchronizationDataTransfer) {
            return $synchronizationDataTransfer->getData()[static::KEY_CATEGORY_NODE_ID];
        }, $synchronizationDataTransfers);

        $this->assertSame([$categoryNodeId], $categoryNodeIds, 'Synchronization data should be filtered by category node IDs.');
    }

    /**
     * @return void
     */
    public function testGetSynchronizationDataTransfersByCategoryNodeIdsWillReturnDataByLimit(): void
    {
        $expectedCount = 1;
        for ($i = 0; $i < 3; $i++) {
            $categoryTransfer = $this->tester->haveLocalizedCategoryWithStoreRelation();
            $this->tester->haveCategoryNodePageSearchByLocalizedCategory($categoryTransfer);
        }

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getSynchronizationDataTransfersByCategoryNodeIds(0, $expectedCount, []);

        // Assert
        $this->assertCount($expectedCount, $synchronizationDataTransfers, sprintf('Exactly %d category nodes should be found.', $expectedCount));
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCategoryNodePageSearchWriterAsserts(CategoryTransfer $categoryTransfer): void
    {
        $categoryNodePageSearchEntity = $this->tester->findCategoryNodePageSearchEntityByLocalizedCategory($categoryTransfer);
        $this->assertNotNull($categoryNodePageSearchEntity, 'CategoryNodePageSearchEntity should exist.');

        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        $categoryLocalizedAttributesTransfer = $categoryTransfer->getLocalizedAttributes()->offsetGet(0);
        $data = $categoryNodePageSearchEntity->getData();
        $this->assertSame(
            $categoryLocalizedAttributesTransfer->getName(),
            $data[static::KEY_SEARCH_RESULT_DATA][static::KEY_NAME],
            'The name of category in search-result-data does not equals to expected value.',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCategoryNodePageSearchDeleterAsserts(CategoryTransfer $categoryTransfer): void
    {
        $categoryNodePageSearchEntity = $this->tester->findCategoryNodePageSearchEntityByLocalizedCategory($categoryTransfer);
        $this->assertNull($categoryNodePageSearchEntity, 'CategoryNodePageSearchEntity should not exist.');
    }
}
