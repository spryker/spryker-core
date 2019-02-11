<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer;
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
    protected const PRODUCT_ABSTRACT_SKU = '217';

    protected const PACKAGING_TYPE_DEFAULT = 'item';

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
     * @return void
     */
    public function testPublishProductAbstractPackagingDoesNotThrowException(): void
    {
        $this->tester->assertStorageDatabaseTableIsEmpty();

        if (!$this->tester->isProductAbstractCreated(static::PRODUCT_ABSTRACT_SKU)) {
            $this->tester->haveProductAbstract(['sku' => static::PRODUCT_ABSTRACT_SKU]);
        }

        $this->getProductPackagingUnitStorageFacade()->publishProductAbstractPackaging([static::PRODUCT_ABSTRACT_SKU]);

        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testUnpublishProductAbstractPackagingDoesNotThrowException(): void
    {
        $this->tester->assertStorageDatabaseTableIsEmpty();

        if (!$this->tester->isProductAbstractCreated(static::PRODUCT_ABSTRACT_SKU)) {
            $this->tester->haveProductAbstract(['sku' => static::PRODUCT_ABSTRACT_SKU]);
        }

        $this->getProductPackagingUnitStorageFacade()->unpublishProductAbstractPackaging([static::PRODUCT_ABSTRACT_SKU]);

        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractPackagingStorageTransfersByProductAbstractIdsReturnsEmptyWithNoIds(): void
    {
        // Arrange
        $productPackagingUnitStorageFacade = $this->getProductPackagingUnitStorageFacade();

        // Act
        $productAbstractPackagingStorageTransfers = $productPackagingUnitStorageFacade->getProductAbstractPackagingStorageTransfersByProductAbstractIds([]);

        // Assert
        $this->assertEmpty($productAbstractPackagingStorageTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractPackagingStorageTransfersByProductAbstractIdsReturnsDataWithIds(): void
    {
        // Arrange
        $productPackagingUnitStorageFacade = $this->getProductPackagingUnitStorageFacade();

        $productTransfer = $this->tester->haveProduct();
        $productPackagingUnitTypeTransfer = $this->tester->haveProductPackagingUnitType([
            SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE_DEFAULT,
        ]);
        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $productPackagingUnitTypeTransfer->getIdProductPackagingUnitType(),
        ]);
        $this->tester->haveProductPackagingLeadProduct([
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            SpyProductPackagingLeadProductEntityTransfer::FK_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstract(),
        ]);

        // Act
        $productAbstractPackagingStorageTransfers = $productPackagingUnitStorageFacade->getProductAbstractPackagingStorageTransfersByProductAbstractIds([
            $productTransfer->getFkProductAbstract(),
        ]);

        // Assert
        $this->assertNotEmpty($productAbstractPackagingStorageTransfers);
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
