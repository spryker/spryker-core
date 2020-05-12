<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantStorage\Communication\Plugin\Publisher\Merchant\MerchantStoragePublisherPlugin;

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
        $storeRelationTransfer = $this->tester->createStoreRelationTransfer();
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
    public function testMerchantPublishStorageListenerDeleteData(): void
    {
        // Arrange
        $storeRelationTransfer = $this->tester->createStoreRelationTransfer();
        $merchantTransfer1 = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
        ]);
        $merchantTransfer2 = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
        ]);
        $merchantTransfer3 = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer1->getIdMerchant()),
            (new EventEntityTransfer())->setId($merchantTransfer2->getIdMerchant()),
            (new EventEntityTransfer())->setId($merchantTransfer3->getIdMerchant()),
        ];

        $merchantStoragePublisherPlugin = new MerchantStoragePublisherPlugin();
        $merchantStoragePublisherPlugin->handleBulk($eventTransfers, MerchantEvents::MERCHANT_PUBLISH);

        $merchantTransfer2->setIsActive(false);
        $merchantTransfer3->setStoreRelation((new StoreRelationBuilder())->build());

        //Act
        $this->tester->getLocator()->merchant()->facade()->updateMerchant($merchantTransfer2);
        $this->tester->getLocator()->merchant()->facade()->updateMerchant($merchantTransfer3);
        $merchantStoragePublisherPlugin->handleBulk($eventTransfers, MerchantEvents::MERCHANT_PUBLISH);

        //Assert
        $merchantStorageEntities = $this->tester->findMerchantStorageEntitiesByIdMerchants([
            $merchantTransfer1->getIdMerchant(),
            $merchantTransfer2->getIdMerchant(),
            $merchantTransfer3->getIdMerchant(),
        ]);

        $this->assertCount(1, $merchantStorageEntities);
        $this->assertArrayHasKey('id_merchant', $merchantStorageEntities[0]->getData());
    }
}
