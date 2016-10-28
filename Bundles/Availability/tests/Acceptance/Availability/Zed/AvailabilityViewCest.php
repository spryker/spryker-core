<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Availability\Zed;

use Acceptance\Availability\Zed\Tester\AvailabilityTester;
use Acceptance\Availability\Zed\PageObject\AvailabilityPage;

class AvailabilityViewCest
{
    /**
     * @param AvailabilityTester $i
     *
     * @return void
     */
    public function testDisplayViewPage(AvailabilityTester $i)
    {
        $i->wantTo('View selected availability item');
        $i->expect('List of all availability items.');

        $i->amOnPage(sprintf(AvailabilityPage::AVAILABILITY_VIEW_URL, AvailabilityPage::AVAILABILITY_ID));

        $i->wait(1);

        $i->see(AvailabilityPage::PAGE_AVAILABILITY_VIEW_HEADER);
        $i->assertTableWithDataExists(1);

        $i->click("//*[@class=\"dataTables_scrollBody\"]/table/tbody/tr/td[6]/a");
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_EDIT_HEADER);

        $i->amOnPage(sprintf(AvailabilityPage::AVAILABILITY_VIEW_URL, AvailabilityPage::AVAILABILITY_ID));

        $i->click('//*[@id="page-wrapper"]/div[2]/div[2]/div/a');
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_LIST_HEADER);


    }
}
