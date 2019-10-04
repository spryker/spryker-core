<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageBusinessFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacade;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization\ProductPackagingUnitSynchronizationDataBulkPlugin;
use SprykerTest\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnitStorage
 * @group Communication
 * @group Plugin
 * @group Synchronization
 * @group ProductPackagingUnitSynchronizationDataBulkPluginTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitSynchronizationDataBulkPluginTest extends Unit
{
    protected const TEST_INVALID_ID = 111;
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
    public function testGetDataReturnsEmptyArrayWithInvalidIds(): void
    {
        $productPackagingUnitSynchronizationDataBulkPlugin = $this->createProductPackagingUnitSynchronizationDataBulkPlugin();
        $synchronizationDataTransfers = $productPackagingUnitSynchronizationDataBulkPlugin->getData(
            0,
            1,
            [static::TEST_INVALID_ID]
        );

        $this->assertEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetDataReturnsDataWithoutIds(): void
    {
        $this->tester->assertStorageDatabaseTableIsEmpty();
        $this->haveBoxProductPackagingUnit();

        $productConcretePackagingSynchronizationDataPlugin = $this->createProductPackagingUnitSynchronizationDataBulkPlugin();
        $synchronizationDataTransfers = $productConcretePackagingSynchronizationDataPlugin->getData(0, 10);

        $this->assertNotEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    protected function haveBoxProductPackagingUnit(): void
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct();

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
        ]);

        $this->getProductPackagingUnitStorageFacade()->publishProductPackagingUnit([$boxProductConcreteTransfer->getIdProductConcrete()]);
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
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization\ProductPackagingUnitSynchronizationDataBulkPlugin
     */
    protected function createProductPackagingUnitSynchronizationDataBulkPlugin(): ProductPackagingUnitSynchronizationDataBulkPlugin
    {
        return new ProductPackagingUnitSynchronizationDataBulkPlugin();
    }
}
