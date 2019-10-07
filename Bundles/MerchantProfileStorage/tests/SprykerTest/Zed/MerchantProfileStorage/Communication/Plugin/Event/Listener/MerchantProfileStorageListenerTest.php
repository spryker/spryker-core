<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProfile\Dependency\MerchantProfileEvents;
use Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageBusinessFactory;
use Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacade;
use Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener\MerchantProfileStorageActivateListener;
use Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener\MerchantProfileStorageDeactivateListener;
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
        $merchantProfileTransfer = $this->tester->haveMerchantProfile($this->tester->haveMerchant());

        // Action
        $merchantProfileStorageActivateListener = new MerchantProfileStorageActivateListener();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantProfileTransfer->getIdMerchantProfile()),
        ];

        $merchantProfileStorageActivateListener->handleBulk($eventTransfers, MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_PUBLISH);

        // Assert
        $merchantProfileStorageTransfer = $this->tester->findMerchantProfileStorageByIdMerchantProfile($merchantProfileTransfer->getIdMerchantProfile());

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

        // Action
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantProfileTransfer->getIdMerchantProfile()),
        ];
        $merchantProfileStorageActivateListener = new MerchantProfileStorageActivateListener();
        $merchantProfileStorageDeactivateListener = new MerchantProfileStorageDeactivateListener();

        $merchantProfileStorageActivateListener->handleBulk($eventTransfers, MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_PUBLISH);
        $merchantProfileStorageDeactivateListener->handleBulk($eventTransfers, MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_UNPUBLISH);

        // Assert
        $merchantProfileStorageTransfer = $this->tester->findMerchantProfileStorageByIdMerchantProfile($merchantProfileTransfer->getIdMerchantProfile());
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
