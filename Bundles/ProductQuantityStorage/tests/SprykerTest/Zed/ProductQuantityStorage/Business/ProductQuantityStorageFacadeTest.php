<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantityStorage\Business;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductQuantityStorage
 * @group Business
 * @group Facade
 * @group ProductQuantityStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductQuantityStorageFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductQuantityStorage\ProductQuantityStorageBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductQuantityStorage\Business\ProductQuantityStorageFacadeInterface
     */
    protected $productQuantityStorageFacade;

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

        $this->productQuantityStorageFacade = $this->tester->getLocator()->productQuantityStorage()->facade();
    }

    /**
     * @return void
     */
    public function testPublishProductQuantityDoesNotThrowException()
    {
        // TODO: temporary disable until P&S is able to handle storage tests
//        // Assign
//        $product = $productTransfer = $this->tester->haveProduct();
//        $this->tester->haveProductQuantity($product->getIdProductConcrete());
//
//        $productIds = [$productTransfer->getIdProductConcrete()];
//
//        // Act
//        $this->productQuantityStorageFacade->publishProductQuantity($productIds);
//
//        // Assert
//        $this->assertTrue(true);
    }
}
