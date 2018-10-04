<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductListStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorageQuery;
use Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorageQuery;
use ReflectionClass;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductListStorage\Business\ProductListStorageBusinessFactory;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductAbstractStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductConcreteStorageListener;
use Spryker\Zed\ProductListStorage\ProductListStorageConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductListStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductListStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductListStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductListStorage\ProductListStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductAbstractStorageListenerStoreData()
    {
        SpyProductAbstractProductListStorageQuery::create()->filterByFkProductAbstract(42)->delete();
        $beforeCount = SpyProductAbstractProductListStorageQuery::create()->count();

        $productAbstractStorageListener = new ProductAbstractStorageListener();
        $productAbstractStorageListener->setFacade($this->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(42),
        ];
        $productAbstractStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $afterCount = SpyProductAbstractProductListStorageQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteStorageListenerStoreData()
    {
        SpyProductConcreteProductListStorageQuery::create()->filterByFkProduct(42)->delete();
        $beforeCount = SpyProductConcreteProductListStorageQuery::create()->count();

        $productConcreteStorageListener = new ProductConcreteStorageListener();
        $productConcreteStorageListener->setFacade($this->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(42),
        ];
        $productConcreteStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $afterCount = SpyProductConcreteProductListStorageQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        $facade = $this->tester->getFacade();

        $facadeReflection = new ReflectionClass($facade);
        $reflectionProperty = $facadeReflection->getParentClass()->getProperty('factory');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($facade, $this->getFactoryMock());

        return $facade;
    }

    /**
     * @return \Spryker\Zed\ProductListStorage\Business\ProductListStorageBusinessFactory
     */
    public function getFactoryMock()
    {
        $factory = new ProductListStorageBusinessFactory();
        $factory->setConfig($this->getConfigMock());

        return $factory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getConfigMock()
    {
        $configMock = $this->getMockBuilder(ProductListStorageConfig::class)
            ->setMethods(['isSendingToQueue'])
            ->getMock();

        $configMock->method('isSendingToQueue')->willReturn(false);

        return $configMock;
    }
}
