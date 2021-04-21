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
use Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Event\Listener\MerchantOpeningHoursDateScheduleStoragePublishListener;

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
 * @group MerchantOpeningHoursDateScheduleStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class MerchantOpeningHoursDateScheduleStoragePublishListenerTest extends Unit
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
    public function testMerchantOpeningHoursDateScheduleStoragePublishListenerStoresData(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->createMerchantOpeningHoursDateSchedule($merchantTransfer);
        $merchantOpeningHoursDateScheduleStoragePublishListener = new MerchantOpeningHoursDateScheduleStoragePublishListener();
        $merchantOpeningHoursDateScheduleStoragePublishListener->setFacade($this->tester->getFacade());
        $eventTransfers = [
            (new EventEntityBuilder())
                ->build()
                ->setForeignKeys([SpyMerchantOpeningHoursDateScheduleTableMap::COL_FK_MERCHANT => $merchantTransfer->getIdMerchant()]),
        ];

        // Act
        $merchantOpeningHoursDateScheduleStoragePublishListener->handleBulk($eventTransfers, MerchantOpeningHoursEvents::ENTITY_SPY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE_CREATE);

        // Assert
        $this->assertNotNull(
            $this->tester->findMerchantOpeningHoursByMerchantReference($merchantTransfer->getMerchantReference())
        );
    }
}
