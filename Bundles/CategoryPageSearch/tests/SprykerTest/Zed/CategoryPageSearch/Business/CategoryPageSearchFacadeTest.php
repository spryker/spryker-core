<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery;
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
     */
    protected const CATEGORY_STORE_PUBLISH = 'Category.category_store.publish';

    protected const STORE_NAME = 'DE';

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
        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());
        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        $categoryLocalizedAttributesTransfer = $categoryTransfer->getLocalizedAttributes()->offsetGet(0);

        $eventEntityTransfer = (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory());

        // Act
        $this->tester->getFacade()->writeCategoryNodePageSearchCollectionByCategoryStorePublishEvents([$eventEntityTransfer]);

        // Assert
        $categoryNodePageSearchEntity = SpyCategoryNodePageSearchQuery::create()
            ->filterByFkCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->filterByLocale($categoryLocalizedAttributesTransfer->getLocale()->getLocaleName())
            ->findOne();
        $this->assertNotNull($categoryNodePageSearchEntity, 'CategoryNodePageSearchEntity should exist.');

        $data = $categoryNodePageSearchEntity->getData();
        $this->assertSame(
            $categoryLocalizedAttributesTransfer->getName(),
            $data['search-result-data']['name'],
            'The name of category in search-result-data does not equals to expected value.'
        );
    }

    /**
     * @return void
     */
    public function testWriteCategoryNodePageSearchCollectionByCategoryStoreEventsWillWriteSearchData(): void
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
        $this->tester->getFacade()->writeCategoryNodePageSearchCollectionByCategoryStoreEvents([$eventEntityTransfer]);

        // Assert
        $categoryNodePageSearchEntity = SpyCategoryNodePageSearchQuery::create()
            ->filterByFkCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->filterByLocale($categoryLocalizedAttributesTransfer->getLocale()->getLocaleName())
            ->findOne();
        $this->assertNotNull($categoryNodePageSearchEntity, 'CategoryNodePageSearchEntity should exist.');

        $data = $categoryNodePageSearchEntity->getData();
        $this->assertSame(
            $categoryLocalizedAttributesTransfer->getName(),
            $data['search-result-data']['name'],
            'The name of category in search-result-data does not equals to expected value.'
        );
    }
}
