<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantStorage\Communication\Plugin\Event\Listener\MerchantStoragePublishListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group MerchantStorageListenerTest
 * Add your own group annotations below this line
 */
class MerchantStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantStorage\MerchantStorageCommunicationTester
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
    public function testMerchantPublishStorageListenerStoreData(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);

        // Act
        $merchantStoragePublishListener = new MerchantStoragePublishListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];

        $merchantStoragePublishListener->handleBulk($eventTransfers, MerchantEvents::MERCHANT_PUBLISH);

        // Assert
        $merchantStorageEntity = $this->tester->findMerchantStorageEntityByIdMerchant($merchantTransfer->getIdMerchant());

        $this->assertNotNull($merchantStorageEntity);
        $this->assertArrayHasKey('id_merchant', $merchantStorageEntity->getData());
    }

    /**
     * @return void
     */
    public function testMerchantPublishStorageListenerDeleteData(): void
    {
        // Arrange
        $merchantTransfer1 = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $merchantTransfer2 = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer1->getIdMerchant()),
            (new EventEntityTransfer())->setId($merchantTransfer2->getIdMerchant()),
        ];

        $merchantStoragePublishListener = new MerchantStoragePublishListener();
        $merchantStoragePublishListener->handleBulk($eventTransfers, MerchantEvents::MERCHANT_PUBLISH);

        $merchantTransfer2->setIsActive(false);

        //Act
        $this->tester->getLocator()->merchant()->facade()->updateMerchant($merchantTransfer2);
        $merchantStoragePublishListener->handleBulk($eventTransfers, MerchantEvents::MERCHANT_PUBLISH);

        //Assert
        $merchantStorageEntities = $this->tester->findMerchantStorageEntitiesByIdMerchants([
            $merchantTransfer1->getIdMerchant(),
            $merchantTransfer2->getIdMerchant(),
        ]);

        $this->assertCount(1, $merchantStorageEntities);
        $this->assertArrayHasKey('id_merchant', $merchantStorageEntities[0]->getData());
    }
}
