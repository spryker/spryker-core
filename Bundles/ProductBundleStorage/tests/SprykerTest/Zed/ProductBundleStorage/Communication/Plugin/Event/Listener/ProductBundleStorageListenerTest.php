<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundleStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductBundleStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductBundleStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundleStorage\ProductBundleStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
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
    public function testProductBundlePublishStorageListenerStoreData(): void
    {
        // Arrange

        // Act

        // Assert
    }

    /**
     * @return void
     */
    public function testProductBundlePublishStorageListenerDeleteData(): void
    {
        // Arrange

        //Act

        //Assert
    }
}
