<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage\Business;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

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
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface
     */
    protected $productPackagingUnitStorageFacade;

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

        $this->productPackagingUnitStorageFacade = $this->tester->getLocator()->productPackagingUnitStorage()->facade();
    }

    /**
     * @return void
     */
    public function testPublishProductAbstractPackagingDoesNotThrowException(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->productPackagingUnitStorageFacade->publishProductAbstractPackaging([217]);

        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testUnpublishProductAbstractPackagingDoesNotThrowException(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->productPackagingUnitStorageFacade->unpublishProductAbstractPackaging([217]);

        $this->assertTrue(true);
    }
}
