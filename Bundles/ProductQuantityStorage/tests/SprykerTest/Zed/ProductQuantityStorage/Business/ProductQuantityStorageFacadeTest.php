<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantityStorage\Business;

use Codeception\Test\Unit;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
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
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        if (!$this->tester->isSuiteProject()) {
            throw new SkippedTestError('Warning: not in suite environment');
        }

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
        // Assign
        $product = $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductQuantity($product->getIdProductConcrete());

        $productIds = [$productTransfer->getIdProductConcrete()];

        // Act
        $this->productQuantityStorageFacade->publishProductQuantity($productIds);

        // Assert
        $this->assertTrue(true);
    }
}
