<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryFilterStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Orm\Zed\ProductCategoryFilter\Persistence\Map\SpyProductCategoryFilterTableMap;
use Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductCategoryFilter\Business\ProductCategoryFilterFacade;
use Spryker\Zed\ProductCategoryFilter\Dependency\ProductCategoryFilterEvents;
use Spryker\Zed\ProductCategoryFilterStorage\Business\ProductCategoryFilterStorageBusinessFactory;
use Spryker\Zed\ProductCategoryFilterStorage\Business\ProductCategoryFilterStorageFacade;
use Spryker\Zed\ProductCategoryFilterStorage\Communication\Plugin\Event\Listener\ProductCategoryFilterPublishStorageListener;
use SprykerTest\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryFilterStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductCategoryFilterStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductCategoryFilterStorageListenerTest extends Unit
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
    public function testProductCategoryFilterPublishStorageListenerStoreData()
    {
        $productCategoryFilterFacade = new ProductCategoryFilterFacade();
        $productCategory = $productCategoryFilterFacade->findProductCategoryFilterByCategoryId(1);
        if ($productCategory->getIdProductCategoryFilter()) {
            $productCategoryFilterFacade->deleteProductCategoryFilterByCategoryId(1);
        }

        $productCategoryFilterFacade->createProductCategoryFilter(
            (new ProductCategoryFilterTransfer())
                ->setFkCategory(1)
                ->setFilterData('price')
        );

        SpyProductCategoryFilterStorageQuery::create()->filterByFkCategory(1)->delete();
        $beforeCount = SpyProductCategoryFilterStorageQuery::create()->count();

        $productCategoryFilterPublishStorageListener = new ProductCategoryFilterPublishStorageListener();
        $productCategoryFilterPublishStorageListener->setFacade($this->getProductCategoryFilterStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductCategoryFilterTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];
        $productCategoryFilterPublishStorageListener->handleBulk($eventTransfers, ProductCategoryFilterEvents::ENTITY_SPY_PRODUCT_CATEGORY_FILTER_CREATE);

        // Assert
        $this->assertProductCategoryFilterStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterStorage\Business\ProductCategoryFilterStorageFacade
     */
    protected function getProductCategoryFilterStorageFacade()
    {
        $factory = new ProductCategoryFilterStorageBusinessFactory();
        $factory->setConfig(new ProductCategoryFilterStorageConfigMock());

        $facade = new ProductCategoryFilterStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductCategoryFilterStorage($beforeCount)
    {
        $productCategoryFilterStorageCount = SpyProductCategoryFilterStorageQuery::create()->count();
        $this->assertSame($beforeCount + 1, $productCategoryFilterStorageCount);
        $spyProductCategoryFilterStorage = SpyProductCategoryFilterStorageQuery::create()->orderByIdProductCategoryFilterStorage()->findOne();
        $this->assertNotNull($spyProductCategoryFilterStorage);
    }
}
