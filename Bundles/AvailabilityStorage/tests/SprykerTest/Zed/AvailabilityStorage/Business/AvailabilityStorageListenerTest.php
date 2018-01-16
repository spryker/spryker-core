<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery;
use Propel\Runtime\Propel;
use Silex\Application;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener\AvailabilityStorageListener;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityStorage
 * @group Business
 * @group AvailabilityStorageListenerTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\AvailabilityStorage\AvailabilityStorageBusinessTester $tester
 */
class AvailabilityStorageListenerTest extends Unit
{

    /**
     * @return void
     */
    protected function setUp()
    {
        Propel::disableInstancePooling();
        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        SpyAvailabilityStorageQuery::create()->deleteall();
    }

    /**
     * @return void
     */
    public function testAvailabilityStorageListenerStoreData()
    {
        $availabilityStorageCount = SpyAvailabilityStorageQuery::create()->count();
        $this->assertSame(0, $availabilityStorageCount);

        // Act
        $availabilityStorageListener = new AvailabilityStorageListener(false);
        $eventTransfers = [
            (new EventEntityTransfer())->setId(1)
        ];
        $availabilityStorageListener->handleBulk($eventTransfers, AvailabilityEvents::AVAILABILITY_ABSTRACT_PUBLISH);

        // Assert
        $availabilityStorageCount = SpyAvailabilityStorageQuery::create()->count();
        $this->assertSame(1, $availabilityStorageCount);
    }
}
