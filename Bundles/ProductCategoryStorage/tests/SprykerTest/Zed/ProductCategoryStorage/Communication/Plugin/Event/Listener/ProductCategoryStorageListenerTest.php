<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageBusinessFactory;
use Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacade;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryAttributeStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryUrlStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\ProductCategoryPublishStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\ProductCategoryStorageListener;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainer;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\ProductCategoryStorage\ProductCategoryStorageConfigMock;

/**
 * Auto-generated group annotations
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
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
    }

    /**
     * @return void
     */
    public function testProductCategoryPublishStorageListenerStoreData()
    {
        SpyProductAbstractCategoryStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractCategoryStorageQuery::create()->count();

        $productCategoryPublishStorageListener = new ProductCategoryPublishStorageListener();
        $productCategoryPublishStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productCategoryPublishStorageListener->handleBulk($eventTransfers, ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH);

        // Assert
        $this->assertProductAbstractCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductCategoryStorageListenerStoreData()
    {
        SpyProductAbstractCategoryStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractCategoryStorageQuery::create()->count();

        $productCategoryStorageListener = new ProductCategoryStorageListener();
        $productCategoryStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productCategoryStorageListener->handleBulk($eventTransfers, ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE);

        // Assert
        $this->assertProductAbstractCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryNodeStorageListenerStoreData()
    {
        $productAbstractIds = $this->findProductAbstractIdsByIdCategory(1);
        SpyProductAbstractCategoryStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractCategoryStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeStorageListener();
        $categoryNodeStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryNodeTableMap::COL_FK_CATEGORY => 7,
            ]),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryUrlStorageListenerStoreData()
    {
        $productAbstractIds = $this->findProductAbstractIdsByIdCategory(1);
        SpyProductAbstractCategoryStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractCategoryStorageQuery::create()->count();

        $categoryUrlStorageListener = new CategoryUrlStorageListener();
        $categoryUrlStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => 7,
            ])
                ->setModifiedColumns([
                    SpyUrlTableMap::COL_URL,
                ]),
        ];
        $categoryUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryAttributeStorageListenerStoreData()
    {
        $productAbstractIds = $this->findProductAbstractIdsByIdCategory(1);
        SpyProductAbstractCategoryStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractCategoryStorageQuery::create()->count();

        $categoryAttributeStorageListener = new CategoryAttributeStorageListener();
        $categoryAttributeStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 7,
            ])
                ->setModifiedColumns([
                    SpyCategoryAttributeTableMap::COL_NAME,
                ]),
        ];
        $categoryAttributeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryStorageListenerStoreData()
    {
        $productAbstractIds = $this->findProductAbstractIdsByIdCategory(1);
        SpyProductAbstractCategoryStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractCategoryStorageQuery::create()->count();

        $categoryStorageListener = new CategoryStorageListener();
        $categoryStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(7)
                ->setModifiedColumns([
                    SpyCategoryTableMap::COL_IS_ACTIVE,
                ]),
        ];
        $categoryStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacade
     */
    protected function getProductCategoryStorageFacade()
    {
        $factory = new ProductCategoryStorageBusinessFactory();
        $factory->setConfig(new ProductCategoryStorageConfigMock());

        $facade = new ProductCategoryStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractStorage($beforeCount)
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertSame($beforeCount + 40, $productCategoryStorageCount);
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractCategoryStorage($beforeCount)
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertSame($beforeCount + 2, $productCategoryStorageCount);
        $spyProductAbstractCategoryStorage = SpyProductAbstractCategoryStorageQuery::create()->orderByIdProductAbstractCategoryStorage()->findOneByFkProductAbstract(1);
        $this->assertNotNull($spyProductAbstractCategoryStorage);
        $data = $spyProductAbstractCategoryStorage->getData();
        $this->assertSame(2, count($data['categories']));
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    protected function findProductAbstractIdsByIdCategory($idCategory)
    {
        $productCategoryQueryContainer = new ProductCategoryStorageQueryContainer();
        $productCategoryFacade = $this->getProductCategoryStorageFacade();
        $categoryIds = $productCategoryQueryContainer->queryCategoryIdsByNodeIds([$idCategory])->find()->getData();
        $relatedCategoryIds = $productCategoryFacade->getRelatedCategoryIds($categoryIds);

        return $productCategoryQueryContainer->queryProductAbstractIdsByCategoryIds($relatedCategoryIds)->find()->getData();
    }
}
