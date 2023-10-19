<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Store\StoreDependencyProvider;
use SprykerTest\Zed\ProductCategoryStorage\ProductCategoryStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryStorage
 * @group Business
 * @group Facade
 * @group WriteCollectionByCategoryStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByCategoryStoreEventsTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_AT = 'AT';

    /**
     * @var int
     */
    protected const FAKE_ID_CATEGORY = 6666;

    /**
     * @var string
     */
    protected const ASSET_MESSAGE_COUNT_IS_WRONG = 'Product Category Storage record count is wrong.';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_EN = 'en_US';

    /**
     * @var \SprykerTest\Zed\ProductCategoryStorage\ProductCategoryStorageBusinessTester
     */
    protected ProductCategoryStorageBusinessTester $tester;

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
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->cleanStaticProperty();
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEvents(): void
    {
        $this->markTestSkipped('Debugging and refactoring requires.');

        // Arrange
        $categoryTransfer = $this->getCategoryTransfer();
        $productConcreteTransfer = $this->tester->haveFullProduct();

        $this->tester->assignProductToCategory(
            $categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryStoreTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            1,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEventsWithFakeIdCategory(): void
    {
        // Arrange
        $categoryTransfer = $this->getCategoryTransfer();
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract(),
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
            static::ASSET_MESSAGE_COUNT_IS_WRONG,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEventsWithTwoStoreRelations(): void
    {
        // Arrange
        $this->tester->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, []);
        $categoryTransfer = $this->getCategoryTransfer();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_AT,
            StoreTransfer::AVAILABLE_LOCALE_ISO_CODES => [static::LOCALE_DE, static::LOCALE_EN],
        ]);

        $this->tester->haveCategoryStoreRelation(
            $categoryTransfer->getIdCategory(),
            $storeTransfer->getIdStore(),
        );

        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryStoreTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            2,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG,
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByCategoryStoreEventsWithoutCurrentStoreRelation(): void
    {
        // Arrange
        $this->tester->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, []);
        $storeTransfer = $this->tester->haveStore(
            [
                StoreTransfer::NAME => static::STORE_AT,
                StoreTransfer::AVAILABLE_LOCALE_ISO_CODES => [static::LOCALE_DE, static::LOCALE_EN],
            ],
        );

        $categoryTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $this->tester->getRootCategoryNode()->toArray(),
        ]);

        $this->tester->haveCategoryStoreRelation(
            $categoryTransfer->getIdCategory(),
            $storeTransfer->getIdStore(),
        );

        $productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $categoryTransfer->getIdCategory(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryStoreTableMap::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByCategoryStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertCount(
            0,
            $this->tester->getProductAbstractCategoryStorageEntities($productConcreteTransfer),
            static::ASSET_MESSAGE_COUNT_IS_WRONG,
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function getCategoryTransfer(): CategoryTransfer
    {
        $storeTransfer = $this->tester->haveStore(
            [
                StoreTransfer::NAME => static::STORE_DE,
                StoreTransfer::AVAILABLE_LOCALE_ISO_CODES => [static::LOCALE_DE, static::LOCALE_EN],
            ],
        );

        $categoryTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $this->tester->getRootCategoryNode()->toArray(),
        ]);

        $this->tester->haveCategoryStoreRelation(
            $categoryTransfer->getIdCategory(),
            $storeTransfer->getIdStore(),
        );

        return $categoryTransfer;
    }
}
