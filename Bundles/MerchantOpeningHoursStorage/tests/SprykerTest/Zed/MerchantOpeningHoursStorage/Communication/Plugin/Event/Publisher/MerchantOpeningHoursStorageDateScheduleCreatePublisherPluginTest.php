<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\EventEntityBuilder;
use Orm\Zed\MerchantOpeningHours\Persistence\Map\SpyMerchantOpeningHoursDateScheduleTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantOpeningHours\Dependency\MerchantOpeningHoursEvents;
use Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Publisher\MerchantOpeningHoursStorageDateScheduleCreatePublisherPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOpeningHoursStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group MerchantOpeningHoursStorageDateScheduleCreatePublisherPluginTest
 * Add your own group annotations below this line
 */
class MerchantOpeningHoursStorageDateScheduleCreatePublisherPluginTest extends Unit
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
    }

    /**
     * @return void
     */
    public function testMerchantOpeningHoursStorageDateScheduleCreatePublisherPluginStoresData(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantOpeningHoursDateScheduleEntity = $this->tester->createMerchantOpeningHoursDateSchedule($merchantTransfer);
        $merchantOpeningHoursStorageDateScheduleCreatePublisherPlugin = new MerchantOpeningHoursStorageDateScheduleCreatePublisherPlugin();
        $merchantOpeningHoursStorageDateScheduleCreatePublisherPlugin->setFacade($this->tester->getFacade());
        $eventTransfers = [
            (new EventEntityBuilder())
                ->build()
                ->setForeignKeys([SpyMerchantOpeningHoursDateScheduleTableMap::COL_FK_MERCHANT => $merchantTransfer->getIdMerchant()]),
        ];

        // Act
        $merchantOpeningHoursStorageDateScheduleCreatePublisherPlugin->handleBulk($eventTransfers, MerchantOpeningHoursEvents::ENTITY_SPY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE_CREATE);

        // Assert
        $this->assertNotNull(
            $this->tester->findMerchantOpeningHoursByFkMerchant($merchantOpeningHoursDateScheduleEntity->getFkMerchant())
        );
    }
}
