<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Braintree\Zed;

use Acceptance\Availability\Zed\Tester\AvailabilityTester;
use Acceptance\Availability\Zed\PageObject\AvailabilityPage;

class AvailabilityViewCest
{
    /**
     * @param AvailabilityTester $i
     *
     * @return void
     */
    public function testDisplayListPage(AvailabilityTester $i)
    {
        $i->wantTo('View selected availability item');
        $i->expect('List of all availability items.');

        $i->amOnPage(sprintf(AvailabilityPage::AVAILABILITY_VIEW_URL, AvailabilityPage::AVAILABILITY_ID));

        $i->wait(1);

        $i->see('Detail Availability');
        $i->assertTableWithDataExists(1);
    }
}
