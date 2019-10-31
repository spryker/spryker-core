<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProfile\Dependency\MerchantProfileEvents;
use Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageBusinessFactory;
use Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacade;
use Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener\MerchantProfileStoragePublishListener;
use Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener\MerchantProfileStorageUnpublishListener;
use SprykerTest\Zed\MerchantProfileStorage\MerchantProfileStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfileStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group MerchantProfileStorageListenerTest
 * Add your own group annotations below this line
 */
class MerchantProfileStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProfileStorage\MerchantProfileStorageCommunicationTester
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
    public function testMerchantProfilePublishStorageListenerStoreData(): void
    {
        // Arrange
        $merchantProfileTransfer = $this->tester->haveMerchantProfile($this->tester->haveMerchant(), [MerchantProfileTransfer::IS_ACTIVE => true]);

        // Act
        $merchantProfileStoragePublishListener = new MerchantProfileStoragePublishListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantProfileTransfer->getIdMerchantProfile()),
        ];

        $merchantProfileStoragePublishListener->handleBulk($eventTransfers, MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_PUBLISH);

        // Assert
        $merchantProfileStorageTransfer = $this->tester->findMerchantProfileStorageByIdMerchant($merchantProfileTransfer->getFkMerchant());

        $this->assertNotNull($merchantProfileStorageTransfer);
        $this->assertArrayHasKey('id_merchant_profile', $merchantProfileStorageTransfer->getData());
    }

    /**
     * @return void
     */
    public function testMerchantProfileUnpublishStorageListenerStoreData(): void
    {
        // Arrange
        $merchantProfileTransfer = $this->tester->haveMerchantProfile(
            $this->tester->haveMerchant(),
            ['is_active' => false]
        );

        // Act
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantProfileTransfer->getIdMerchantProfile()),
        ];
        $merchantProfileStorageUnpublishListener = new MerchantProfileStorageUnpublishListener();

        $merchantProfileStorageUnpublishListener->handleBulk($eventTransfers, MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_UNPUBLISH);

        // Assert
        $merchantProfileStorageTransfer = $this->tester->findMerchantProfileStorageByIdMerchant($merchantProfileTransfer->getFkMerchant());
        $this->assertNull($merchantProfileStorageTransfer);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacade
     */
    protected function getMerchantProfileStorageFacade(): MerchantProfileStorageFacade
    {
        $factory = new MerchantProfileStorageBusinessFactory();
        $factory->setConfig(new MerchantProfileStorageConfigMock());

        $facade = new MerchantProfileStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }
}
