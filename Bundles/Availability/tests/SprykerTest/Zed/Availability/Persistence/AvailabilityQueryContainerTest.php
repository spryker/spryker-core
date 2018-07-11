<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Spryker\Zed\Availability\Persistence\AvailabilityPersistenceFactory;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Availability
 * @group Persistence
 * @group AvailabilityQueryContainerTest
 * Add your own group annotations below this line
 */
class AvailabilityQueryContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testQueryAllAvailabilityAbstractsReturnCorrectQueryObject()
    {
        $availabilityQueryContainer = new AvailabilityQueryContainer();
        $availabilityQueryContainer->setFactory(new AvailabilityPersistenceFactory());
        $query = $availabilityQueryContainer->queryAllAvailabilityAbstracts();

        $this->assertInstanceOf(SpyAvailabilityAbstractQuery::class, $query);
    }
}
