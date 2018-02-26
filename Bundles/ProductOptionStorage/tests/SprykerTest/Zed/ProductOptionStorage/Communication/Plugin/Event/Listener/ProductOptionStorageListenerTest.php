<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValuePriceTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductOption\Dependency\ProductOptionEvents;
use Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageBusinessFactory;
use Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacade;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionGroupStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionPublishStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionValuePriceStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionValueStorageListener;
use Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainer;
use SprykerTest\Zed\ProductOptionStorage\ProductOptionStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOptionStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductOptionStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductOptionStorageListenerTest extends Unit
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
    public function testProductOptionPublishStorageListenerStoreData()
    {
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionPublishStorageListener = new ProductOptionPublishStorageListener();
        $productOptionPublishStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productOptionPublishStorageListener->handleBulk($eventTransfers, ProductOptionEvents::PRODUCT_ABSTRACT_PRODUCT_OPTION_PUBLISH);

        // Assert
        $this->assertProductAbstractOptionStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductOptionStorageListenerStoreData()
    {
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionStorageListener = new ProductOptionStorageListener();
        $productOptionStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productOptionStorageListener->handleBulk($eventTransfers, ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_CREATE);

        // Assert
        $this->assertProductAbstractOptionStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductOptionGroupStorageListenerStoreData()
    {
        $productOptionStorageQueryContainer = new ProductOptionStorageQueryContainer();
        $productAbstractIds = $productOptionStorageQueryContainer->queryProductAbstractIdsByProductGroupOptionByIds([1])->find()->getData();
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionGroupStorageListener = new ProductOptionGroupStorageListener();
        $productOptionGroupStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productOptionGroupStorageListener->handleBulk($eventTransfers, ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_GROUP_UPDATE);

        // Assert
        $this->assertProductAbstractOptionGroupStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductOptionValueStorageListenerStoreData()
    {
        $productOptionStorageQueryContainer = new ProductOptionStorageQueryContainer();
        $productAbstractIds = $productOptionStorageQueryContainer->queryProductAbstractIdsByProductGroupOptionByIds([1])->find()->getData();
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionValueStorageListener = new ProductOptionValueStorageListener();
        $productOptionValueStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOptionValueTableMap::COL_FK_PRODUCT_OPTION_GROUP => 1,
            ]),
        ];
        $productOptionValueStorageListener->handleBulk($eventTransfers, ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_CREATE);

        // Assert
        $this->assertProductAbstractOptionGroupStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductOptionValuePriceStorageListenerStoreData()
    {
        $productOptionStorageQueryContainer = new ProductOptionStorageQueryContainer();
        $productAbstractIds = $productOptionStorageQueryContainer->queryProductAbstractIdsByProductGroupOptionByIds([1])->find()->getData();
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionValuePriceStorageListener = new ProductOptionValuePriceStorageListener();
        $productOptionValuePriceStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOptionValuePriceTableMap::COL_FK_PRODUCT_OPTION_VALUE => 1,
            ]),
        ];
        $productOptionValuePriceStorageListener->handleBulk($eventTransfers, ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_PRICE_CREATE);

        // Assert
        $this->assertProductAbstractOptionGroupStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacade
     */
    protected function getProductOptionStorageFacade()
    {
        $factory = new ProductOptionStorageBusinessFactory();
        $factory->setConfig(new ProductOptionStorageConfigMock());

        $facade = new ProductOptionStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractOptionGroupStorage($beforeCount)
    {
        $productOptionStorageCount = SpyProductAbstractOptionStorageQuery::create()->count();
        $this->assertSame($beforeCount + 348, $productOptionStorageCount);
    }

        /**
         * @param int $beforeCount
         *
         * @return void
         */
    protected function assertProductAbstractOptionStorage($beforeCount)
    {
        $productOptionStorageCount = SpyProductAbstractOptionStorageQuery::create()->count();
        $this->assertSame($beforeCount + 2, $productOptionStorageCount);
        $spyProductAbstractOptionStorage = SpyProductAbstractOptionStorageQuery::create()->orderByIdProductAbstractOptionStorage()->findOneByFkProductAbstract(1);
        $this->assertNotNull($spyProductAbstractOptionStorage);
        $data = $spyProductAbstractOptionStorage->getData();
        $this->assertSame(2, count($data['product_option_groups']));
    }
}
