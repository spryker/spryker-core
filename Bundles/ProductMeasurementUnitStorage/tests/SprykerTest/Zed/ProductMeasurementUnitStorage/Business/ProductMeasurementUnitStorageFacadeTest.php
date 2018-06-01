<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitStorage\Business;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnitStorage
 * @group Business
 * @group Facade
 * @group ProductMeasurementUnitStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductMeasurementUnitStorageFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\Business\ProductMeasurementUnitStorageFacadeInterface
     */
    protected $productMeasurementUnitStorageFacade;

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

        $this->productMeasurementUnitStorageFacade = $this->tester->getLocator()->productMeasurementUnitStorage()->facade();
    }

    /**
     * @return void
     */
    public function testPublishProductMeasurementUnitDoesNotThrowException(): void
    {
        // TODO: temporary disable until P&S is able to handle storage tests
//        // Assign
//        $code = 'MYCODE' . random_int(1, 100);
//        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
//            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
//        ]);
//
//        $measurementUnitIds = [$productMeasurementUnitTransfer->getIdProductMeasurementUnit()];
//
//        // Act
//        $this->productMeasurementUnitStorageFacade->publishProductMeasurementUnit($measurementUnitIds);
//
//        // Assert
//        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testPublishProductConcreteMeasurementUnitDoesNotThrowException(): void
    {
        // TODO: temporary disable until P&S is able to handle storage tests
//        // Assign
//        $code = 'MYCODE' . random_int(1, 100);
//        $productTransfer = $this->tester->haveProduct();
//        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
//            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
//        ]);
//
//        $this->tester->haveProductMeasurementBaseUnit(
//            $productTransfer->getFkProductAbstract(),
//            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
//        );
//
//        $productIds = [$productTransfer->getIdProductConcrete()];
//
//        // Act
//        $this->productMeasurementUnitStorageFacade->publishProductConcreteMeasurementUnit($productIds);
//
//        // Assert
//        $this->assertTrue(true);
    }
}
