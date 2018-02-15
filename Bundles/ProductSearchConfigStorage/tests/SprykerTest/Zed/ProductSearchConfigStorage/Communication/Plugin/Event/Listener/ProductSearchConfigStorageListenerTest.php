<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearchConfigStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents;
use Spryker\Zed\ProductSearchConfigStorage\Business\ProductSearchConfigStorageBusinessFactory;
use Spryker\Zed\ProductSearchConfigStorage\Business\ProductSearchConfigStorageFacade;
use Spryker\Zed\ProductSearchConfigStorage\Communication\Plugin\Event\Listener\ProductSearchConfigStorageListener;
use SprykerTest\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductSearchConfigStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductSearchConfigStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductSearchConfigStorageListenerTest extends Unit
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
    public function testProductSearchConfigStorageListenerStoreData()
    {
        SpyProductSearchConfigStorageQuery::create()->deleteAll();
        $productSearchConfigStorageListener = new ProductSearchConfigStorageListener();
        $productSearchConfigStorageListener->setFacade($this->getProductSearchConfigStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer()),
        ];
        $productSearchConfigStorageListener->handleBulk($eventTransfers, ProductSearchEvents::PRODUCT_SEARCH_CONFIG_PUBLISH);

        // Assert
        $this->assertProductSearchConfigStorage();
    }

    /**
     * @return \Spryker\Zed\ProductSearchConfigStorage\Business\ProductSearchConfigStorageFacade
     */
    protected function getProductSearchConfigStorageFacade()
    {
        $factory = new ProductSearchConfigStorageBusinessFactory();
        $factory->setConfig(new ProductSearchConfigStorageConfigMock());

        $facade = new ProductSearchConfigStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertProductSearchConfigStorage()
    {
        $productSearchConfigStorageCount = SpyProductSearchConfigStorageQuery::create()->count();
        $this->assertSame(1, $productSearchConfigStorageCount);
        $spyProductSearchConfigStorage = SpyProductSearchConfigStorageQuery::create()->orderByIdProductSearchConfigStorage()->findOne();
        $this->assertNotNull($spyProductSearchConfigStorage);
        $data = $spyProductSearchConfigStorage->getData();
        $this->assertSame(5, count($data['facet_configs']));
    }
}
