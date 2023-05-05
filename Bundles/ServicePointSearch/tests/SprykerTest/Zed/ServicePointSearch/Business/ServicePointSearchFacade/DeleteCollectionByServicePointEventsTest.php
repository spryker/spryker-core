<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Business\ServicePointSearchFacade;

use Codeception\Test\Unit;
use SprykerTest\Zed\ServicePointSearch\ServicePointSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Business
 * @group ServicePointSearchFacade
 * @group DeleteCollectionByServicePointEventsTest
 * Add your own group annotations below this line
 */
class DeleteCollectionByServicePointEventsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePointSearch\ServicePointSearchBusinessTester
     */
    protected ServicePointSearchBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependencies();
        $this->tester->cleanUpDatabase();
    }

    /**
     * @return void
     */
    public function testDeleteCollectionByServicePointEventsShouldCleanupSearchTable(): void
    {
        // Arrange
        $servicePointIds = $this->tester->createTwoServicePointsForTwoStores();
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($servicePointIds);

        // Act
        $this->tester->getFacade()->deleteCollectionByServicePointEvents($eventEntityTransfers);

        // Assert
        $this->assertCount(0, $this->tester->getServicePointSearchEntitiesByServicePointIds($servicePointIds));
    }

    /**
     * @return void
     */
    public function testDeleteCollectionByServicePointEventsDoesNothingForEmptyEventTransfers(): void
    {
        // Arrange
        $servicePointIds = $this->tester->createTwoServicePointsForTwoStores();

        // Act
        $this->tester->getFacade()->deleteCollectionByServicePointEvents([]);

        // Assert
        $this->assertSame(4, $this->tester->getServicePointSearchEntitiesByServicePointIds($servicePointIds)->count());
    }
}
