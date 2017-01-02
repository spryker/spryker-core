<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\AvailabilityGui\Zed;

use Acceptance\AvailabilityGui\Zed\PageObject\AvailabilityPage;
use Acceptance\AvailabilityGui\Zed\Tester\AvailabilityTester;

/**
 * @group Acceptance
 * @group AvailabilityGui
 * @group Zed
 * @group AvailabilityListCest
 */
class AvailabilityListCest
{

    /**
     * @param \Acceptance\AvailabilityGui\Zed\Tester\AvailabilityTester $i
     *
     * @return void
     */
    public function testDisplayListPage(AvailabilityTester $i)
    {
        $i->wantTo('Open availability list');
        $i->expect('List of all availability items');

        $i->amOnPage(AvailabilityPage::AVAILABILITY_LIST_URL);

        $i->wait(1);

        $i->see(AvailabilityPage::PAGE_AVAILABILITY_LIST_HEADER);
        $i->assertTableWithDataExists();

        $i->click("//*[@class=\"dataTables_scrollBody\"]/table/tbody/tr/td[7]/a");
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_VIEW_HEADER);
    }

}
