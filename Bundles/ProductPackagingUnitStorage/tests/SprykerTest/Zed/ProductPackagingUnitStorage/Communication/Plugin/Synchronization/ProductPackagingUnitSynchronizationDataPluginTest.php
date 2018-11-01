<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageBusinessFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacade;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization\ProductPackagingUnitSynchronizationDataPlugin;
use SprykerTest\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnitStorage
 * @group Communication
 * @group Plugin
 * @group Synchronization
 * @group ProductPackagingUnitSynchronizationDataPluginTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitSynchronizationDataPluginTest extends Unit
{
    protected const TEST_INVALID_ID = 111;
    protected const PACKAGING_TYPE_DEFAULT = 'item';
    protected const PACKAGING_TYPE = 'box';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
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
    public function testGetDataWithIds(): void
    {
        $productPackagingUnitSynchronizationDataPlugin = $this->getProductPackagingUnitSynchronizationDataPlugin();
        $synchronizationDataTransfers = $productPackagingUnitSynchronizationDataPlugin->getData([
            static::TEST_INVALID_ID,
        ]);

        $this->assertEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetDataWithoutIds(): void
    {
        $this->tester->assertStorageDatabaseTableIsEmpty();
        $this->haveBoxProductPackagingUnit();

        $productPackagingUnitSynchronizationDataPlugin = $this->getProductPackagingUnitSynchronizationDataPlugin();
        $synchronizationDataTransfers = $productPackagingUnitSynchronizationDataPlugin->getData();

        $this->assertNotEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    protected function haveBoxProductPackagingUnit(): void
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $this->tester->haveProductPackagingLeadProduct([
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);
        $itemProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE_DEFAULT]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $itemProductPackagingUnitType->getIdProductPackagingUnitType(),
        ]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
        ]);

        $this->getProductPackagingUnitStorageFacade()->publishProductAbstractPackaging([$boxProductConcreteTransfer->getFkProductAbstract()]);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacade
     */
    protected function getProductPackagingUnitStorageFacade()
    {
        $factory = new ProductPackagingUnitStorageBusinessFactory();
        $factory->setConfig(new ProductPackagingUnitStorageConfigMock());

        $facade = new ProductPackagingUnitStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization\ProductPackagingUnitSynchronizationDataPlugin
     */
    protected function getProductPackagingUnitSynchronizationDataPlugin(): ProductPackagingUnitSynchronizationDataPlugin
    {
        return new ProductPackagingUnitSynchronizationDataPlugin();
    }
}
