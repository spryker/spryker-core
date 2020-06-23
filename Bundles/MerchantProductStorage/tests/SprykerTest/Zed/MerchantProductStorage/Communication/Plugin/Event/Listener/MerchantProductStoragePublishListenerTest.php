<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProduct\Dependency\MerchantProductEvents;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener\MerchantProductStoragePublishListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group MerchantProductStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class MerchantProductStoragePublishListenerTest extends Unit
{
    /**
     * @var \Spryker\Zed\MerchantProductStorage\Communication\Plugin\Event\Listener\MerchantProductStoragePublishListener
     */
    protected $merchantProductStoragePublishListener;

    /**
     * @var \SprykerTest\Zed\MerchantProductStorage\MerchantProductStorageTester
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

        $this->merchantProductStoragePublishListener = new MerchantProductStoragePublishListener();
    }

    /**
     * @return void
     */
    public function testMerchantProductStoragePublishListener(): void
    {
        //Arrange
        $expectedCount = 1;
        $merchantProductTransfer = $this->tester->haveMerchantProduct();

        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantProductTransfer->getIdProductAbstract()),
        ];

        //Act
        $this->merchantProductStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductEvents::MERCHANT_PRODUCT_ABSTRACT_KEY_PUBLISH
        );
        $count = $this->tester->countMerchantProductAbstract($merchantProductTransfer->getIdProductAbstract());

        //Assert
        $this->assertSame($expectedCount, $count);
    }
}
