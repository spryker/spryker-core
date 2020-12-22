<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage\Communication\Plugin\Publisher\Category;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\Category\CategoryWritePublisherPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group Category
 * @group CategoryWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class CategoryWritePublisherPluginTest extends Unit
{
    /**
     * @uses \Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants::CATEGORY_STORE_PUBLISH,
     */
    protected const CATEGORY_STORE_PUBLISH = 'Category.category_store.publish';
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\CategoryStorage\CategoryStorageCommunicationTester
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
    public function testHandleBulkWillWriteStorageData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());
        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        $categoryLocalizedAttributesTransfer = $categoryTransfer->getLocalizedAttributes()->offsetGet(0);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());
        $categoryWriterPublisherPlugin = new CategoryWritePublisherPlugin();

        // Act
        $categoryWriterPublisherPlugin->handleBulk([$eventEntityTransfer], static::CATEGORY_STORE_PUBLISH);

        // Arrange
        $categoryNodeStorageEntity = SpyCategoryNodeStorageQuery::create()
            ->filterByFkCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->filterByStore($storeTransfer->getName())
            ->filterByLocale($categoryLocalizedAttributesTransfer->getLocale()->getLocaleName())
            ->findOne();

        $this->assertNotNull($categoryNodeStorageEntity);
        $data = $categoryNodeStorageEntity->getData();
        $this->assertSame($categoryLocalizedAttributesTransfer->getName(), $data['name']);
    }
}
