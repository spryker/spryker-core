<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\EventEntityBuilder;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig;
use Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Publisher\MerchantOpeningHours\MerchantOpeningHoursWritePublisherPlugin;
use Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageDependencyProvider;
use Spryker\Zed\Testify\Locator\Business\Container as SprykerContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOpeningHoursStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group MerchantOpeningHoursWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class MerchantOpeningHoursWritePublisherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantOpeningHoursTablesIsEmpty();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->setDependency(
            MerchantOpeningHoursStorageDependencyProvider::FACADE_EVENT_BEHAVIOR,
            function (SprykerContainer $container) {
                return new MerchantOpeningHoursStorageToEventBehaviorFacadeBridge(
                    $container->getLocator()->eventBehavior()->facade(),
                );
            },
        );
    }

    /**
     * @return void
     */
    public function testMerchantOpeningHoursWritePublisherPluginStoresData(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->createMerchantOpeningHoursDateSchedule($merchantTransfer);
        $merchantOpeningHoursStoragePublisher = new MerchantOpeningHoursWritePublisherPlugin();
        $merchantOpeningHoursStoragePublisher->setFacade($this->tester->getFacade());
        $eventTransfers = [
            (new EventEntityBuilder())
                ->build()
                ->setId($merchantTransfer->getIdMerchant()),
        ];

        // Act
        $merchantOpeningHoursStoragePublisher->handleBulk($eventTransfers, MerchantOpeningHoursStorageConfig::MERCHANT_OPENING_HOURS_PUBLISH);

        // Assert
        $this->assertNotNull(
            $this->tester->findMerchantOpeningHoursByFkMerchant($merchantTransfer->getIdMerchant()),
        );
    }
}
