<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Persistence;

use Codeception\Test\Unit;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Communication\Plugin\Category\MainChildrenPropagationCategoryStoreAssignerPlugin;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Subscriber\CategoryPageSearchEventSubscriber;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Spryker\Zed\Synchronization\Communication\Plugin\Queue\SynchronizationSearchQueueMessageProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryPageSearch
 * @group Persistence
 * @group PublishAndSynchronizeTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CategoryPageSearch\CategoryPageSearchPersistenceTester
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

        $this->tester->addEventSubscriber(new CategoryPageSearchEventSubscriber());

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS, [
            PublisherConfig::PUBLISH_QUEUE => new EventQueueMessageProcessorPlugin(),
            CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_QUEUE => new SynchronizationSearchQueueMessageProcessorPlugin(),
        ]);

        $this->tester->setDependency(CategoryDependencyProvider::PLUGIN_CATEGORY_STORE_ASSIGNER, new MainChildrenPropagationCategoryStoreAssignerPlugin());

        $this->categoryTransfer = $this->tester->haveCategory();
        $storeTransfer = $this->tester->haveStore(['name' => 'DE']);
        $this->tester->haveUrl(['fkResourceCategoryNode' => $this->categoryTransfer->getCategoryNode()->getIdCategoryNode()]);
        $this->tester->haveCategoryStoreRelation($this->categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testCategoryPagePublishAndSynchronizeSearch(): void
    {
        $this->assertCreatedEntityIsSynchronizedToSearch();
        $this->assertUpdatedEntityIsUpdatedInSearch();
        $this->assertDeletedEntityIsRemovedFromSearch();
    }

    /**
     * @return void
     */
    protected function assertCreatedEntityIsSynchronizedToSearch(): void
    {
        $this->tester->assertEntityIsPublished(CategoryEvents::ENTITY_SPY_CATEGORY_CREATE, PublisherConfig::PUBLISH_QUEUE);
        $this->tester->assertEntityIsSynchronizedToSearch(CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_QUEUE);

        $this->tester->assertSearchHasKey($this->getExpectedSearchKey(), 'page');
    }

    /**
     * @return void
     */
    protected function assertUpdatedEntityIsUpdatedInSearch(): void
    {
        // Act - Update Category
        $this->categoryTransfer->setIsSearchable(false);
        $this->getCategoryFacade()->update($this->categoryTransfer);

        // Assert
        $this->tester->assertEntityIsPublished(CategoryEvents::ENTITY_SPY_CATEGORY_UPDATE, PublisherConfig::PUBLISH_QUEUE);
        $this->tester->assertEntityIsUpdatedInSearch(CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_QUEUE);

        $this->tester->assertSearchHasKey($this->getExpectedSearchKey(), 'page');
    }

    /**
     * @return void
     */
    protected function assertDeletedEntityIsRemovedFromSearch(): void
    {
        // Act - Delete Category
        $this->getCategoryFacade()->delete($this->categoryTransfer->getIdCategory());

        // Assert
        $this->tester->assertEntityIsPublished(CategoryEvents::ENTITY_SPY_CATEGORY_DELETE, PublisherConfig::PUBLISH_QUEUE);
        $this->tester->assertEntityIsRemovedFromSearch(CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_QUEUE);

        $this->tester->assertSearchNotHasKey($this->getExpectedSearchKey(), 'page');
    }

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function getCategoryFacade(): CategoryFacadeInterface
    {
        /** @var \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade */
        $categoryFacade = $this->tester->getFacade('Category');

        return $categoryFacade;
    }

    /**
     * We test only existence of one store with this key.
     *
     * @return string
     */
    protected function getExpectedSearchKey(): string
    {
        return sprintf('category_node:de:de_de:%d', $this->categoryTransfer->getCategoryNode()->getIdCategoryNode());
    }
}
