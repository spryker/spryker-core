<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Acceptance\Braintree\Zed;

use Acceptance\Availability\Zed\PageObject\AvailabilityPage;
use Acceptance\Availability\Zed\Tester\AvailabilityTester;

/**
 * @group Acceptance
 * @group Availability
 * @group Zed
 * @group AvailabilityCreate
 */
class AvailabilityListCest
{
    /**
     * @param AvailabilityTester $i
     *
     * @return void
     */
    public function testDisplayListPage(AvailabilityTester $i)
    {
        $i->wantTo('Open availability list');
        $i->expect('List of all availability items');

        $i->amOnPage(AvailabilityPage::AVAILABILITY_LIST_URL);

        $i->wait(1);

        $i->see('Availability list');
        $i->assertTableWithDataExists(10);
    }
}
