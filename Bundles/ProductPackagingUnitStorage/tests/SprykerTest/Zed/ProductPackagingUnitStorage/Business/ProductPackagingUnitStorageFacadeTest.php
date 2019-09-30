<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageBusinessFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacade;
use SprykerTest\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnitStorage
 * @group Business
 * @group Facade
 * @group ProductPackagingUnitStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitStorageFacadeTest extends Unit
{
    protected const PACKAGING_TYPE_DEFAULT = 'item';
    protected const PACKAGING_TYPE = 'box';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageBusinessTester
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
     * @doesNotPerformAssertions
     *
     * @return void
     */
    public function testPublishProductPackagingUnitDoesNotThrowException(): void
    {
        $this->tester->assertStorageDatabaseTableIsEmpty();

        $leadProductConcreteTransfer = $this->tester->haveProduct();
        $productConcreteTransfer = $this->tester->haveProduct();
        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::LEAD_PRODUCT_SKU => $leadProductConcreteTransfer->getSku(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $this->getProductPackagingUnitStorageFacade()->publishProductPackagingUnit([$productConcreteTransfer->getIdProductConcrete()]);
    }

    /**
     * @doesNotPerformAssertions
     *
     * @return void
     */
    public function testUnpublishProductPackagingUnitDoesNotThrowException(): void
    {
        $this->tester->assertStorageDatabaseTableIsEmpty();

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->getProductPackagingUnitStorageFacade()->unpublishProductPackagingUnit([$productConcreteTransfer->getIdProductConcrete()]);
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
}
