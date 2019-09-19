<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetTableMap;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetToCategoryImageTableMap;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageTableMap;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorageQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\CategoryImage\Dependency\CategoryImageEvents;
use Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener\CategoryImagePublishStorageListener;
use Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener\CategoryImageSetCategoryImageStorageListener;
use Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener\CategoryImageSetStorageListener;
use Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener\CategoryImageStorageListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryImageStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CategoryImageStorageListenerTest
 * Add your own group annotations below this line
 */
class CategoryImageStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CategoryImageStorage\CategoryImageStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CategoryTransfer
     */
    protected $categoryTransfer;

    /**
     * @var \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected $categoryImageSetTransfer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->setUpData();
    }

    /**
     * @void
     *
     * @return void
     */
    public function testCategoryImagePublishStorageListenerStoreData(): void
    {
        $this->cleanupCategoryImageStorage();
        $beforeCount = SpyCategoryImageStorageQuery::create()->count();
        $categoryImagePublishStorageListener = new CategoryImagePublishStorageListener();
        $categoryImagePublishStorageListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->categoryTransfer->getIdCategory()),
        ];
        $categoryImagePublishStorageListener->handleBulk($eventTransfers, CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_PUBLISH);

        $this->assertCategoryImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryImageSetToCategoryImageStorageListenerStoreData(): void
    {
        $beforeCount = SpyCategoryImageStorageQuery::create()->count();
        $categoryImageSetCategoryImageStorageListener = new CategoryImageSetCategoryImageStorageListener();
        $categoryImageSetCategoryImageStorageListener->setFacade($this->tester->getFacade());
        $categoryImageSetIds = $this->getCategoryImageSetIdsForCategory($this->categoryTransfer);

        $eventTransfers = [];
        foreach ($categoryImageSetIds as $idCategoryImageSet) {
            $eventTransfers[] = (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryImageSetToCategoryImageTableMap::COL_FK_CATEGORY_IMAGE_SET => $idCategoryImageSet,
            ]);
        }

        $categoryImageSetCategoryImageStorageListener->handleBulk($eventTransfers, CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_PUBLISH);

        $this->assertCategoryImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryImageSetStorageListenerStoreData(): void
    {
        $this->cleanupCategoryImageStorage();
        $beforeCount = SpyCategoryImageStorageQuery::create()->count();
        $categoryImageSetStorageListener = new CategoryImageSetStorageListener();
        $categoryImageSetStorageListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryImageSetTableMap::COL_FK_CATEGORY => $this->categoryTransfer->getIdCategory(),
            ]),
        ];
        $categoryImageSetStorageListener->handleBulk($eventTransfers, CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_PUBLISH);

        $this->assertCategoryImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryImageStorageListenerStoreData(): void
    {
        $this->cleanupCategoryImageStorage();
        $beforeCount = SpyCategoryImageStorageQuery::create()->count();
        $categoryImagePublishStorageListener = new CategoryImageStorageListener();
        $categoryImagePublishStorageListener->setFacade($this->tester->getFacade());
        $idCategoryImageColletion = $this->getIdCategoryImageCollectionForCategory($this->categoryTransfer);

        $eventTransfers = [];
        foreach ($idCategoryImageColletion as $idCategoryImage) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($idCategoryImage);
        }

        $categoryImagePublishStorageListener->handleBulk($eventTransfers, CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_PUBLISH);

        $this->assertCategoryImageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryImageSetStorageListenerSortsImagesBySortOrderAsc(): void
    {
        // Assign
        $this->cleanupCategoryImageStorage();
        $categoryImageSetTransfer = $this->tester->createCategoryImageSetWithOrderedImages([3, 1, 0, 2]);
        $categoryTransfer = $this->tester->createCategoryWithImageSet($categoryImageSetTransfer);

        $categoryImagePublishStorageListener = new CategoryImagePublishStorageListener();
        $categoryImagePublishStorageListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory()),
        ];

        // Act
        $categoryImagePublishStorageListener->handleBulk($eventTransfers, CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_PUBLISH);
        $categoryImages = $this->tester->getCategoryImages($categoryTransfer->getIdCategory());

        // Assert
        $this->tester->assertSortingBySortOrder($categoryImages);

        $this->tester->deleteCategoryWithImageSet($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testCategoryImagePublishStorageListenerSortsImagesByIdCategoryImageSetToCategoryImageAsc(): void
    {
        // Assign
        $this->cleanupCategoryImageStorage();
        $categoryImageSetTransfer = $this->tester->createCategoryImageSetWithOrderedImages([0, 0, 0]);
        $categoryTransfer = $this->tester->createCategoryWithImageSet($categoryImageSetTransfer);

        $categoryImagePublishStorageListener = new CategoryImagePublishStorageListener();
        $categoryImagePublishStorageListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory()),
        ];

        // Act
        $categoryImagePublishStorageListener->handleBulk($eventTransfers, CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_PUBLISH);
        $categoryImages = $this->tester->getCategoryImages($categoryTransfer->getIdCategory());

        // Assert
        $this->tester->assertSortingByIdCategoryImageSetToCategoryImage($categoryImages);

        $this->tester->deleteCategoryWithImageSet($categoryTransfer);
    }

    /**
     * @return void
     */
    public function _after()
    {
        parent::_after();

        $this->cleanupCategoryImageStorage();
    }

    /**
     * @return void
     */
    protected function setUpData(): void
    {
        $this->categoryTransfer = $this->tester->haveCategory();
        $this->categoryImageSetTransfer = $this->tester
            ->haveCategoryImageSetForCategory($this->categoryTransfer);
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertCategoryImageStorage($beforeCount)
    {
        $afterCount = SpyCategoryImageStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $categoryImageStorage = SpyCategoryImageStorageQuery::create()
            ->orderByIdCategoryImageStorage()
            ->findOneByFkCategory($this->categoryTransfer->getIdCategory());
        $this->assertNotNull($categoryImageStorage);
        $data = $categoryImageStorage->getData();
        $this->assertSame($this->categoryImageSetTransfer->getName(), $data['image_sets'][0]['name']);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    protected function getCategoryImageSetIdsForCategory(CategoryTransfer $categoryTransfer): array
    {
        return SpyCategoryImageSetQuery::create()
            ->filterByFkCategory($categoryTransfer->getIdCategory())
            ->select(SpyCategoryImageSetTableMap::COL_ID_CATEGORY_IMAGE_SET)
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    protected function getIdCategoryImageCollectionForCategory(CategoryTransfer $categoryTransfer): array
    {
        return SpyCategoryImageQuery::create()
            ->joinSpyCategoryImageSetToCategoryImage()
            ->useSpyCategoryImageSetToCategoryImageQuery()
            ->joinSpyCategoryImageSet()
            ->useSpyCategoryImageSetQuery()
            ->filterByFkCategory($categoryTransfer->getIdCategory())
            ->endUse()
            ->endUse()
            ->select(SpyCategoryImageTableMap::COL_ID_CATEGORY_IMAGE)
            ->find()
            ->getData();
    }

    /**
     * @return void
     */
    protected function cleanupCategoryImageStorage(): void
    {
        SpyCategoryImageStorageQuery::create()
            ->filterByFkCategory(
                $this->categoryTransfer->getIdCategory()
            )
            ->delete();
    }
}
