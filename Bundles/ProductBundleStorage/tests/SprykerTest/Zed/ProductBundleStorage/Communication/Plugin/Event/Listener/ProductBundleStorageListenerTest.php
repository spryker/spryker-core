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
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
        ]);

        // Act
        $merchantStoragePublisherPlugin = new MerchantStoragePublisherPlugin();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];

        $merchantStoragePublisherPlugin->handleBulk($eventTransfers, MerchantEvents::MERCHANT_PUBLISH);

        // Assert
        $merchantStorageEntity = $this->tester->findMerchantStorageEntityByIdMerchant($merchantTransfer->getIdMerchant());

        $this->assertNotNull($merchantStorageEntity);
        $this->assertArrayHasKey('id_merchant', $merchantStorageEntity->getData());
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
