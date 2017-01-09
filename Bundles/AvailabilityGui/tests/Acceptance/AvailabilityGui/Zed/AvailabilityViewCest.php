<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\AvailabilityGui\Zed;

use Acceptance\AvailabilityGui\Zed\PageObject\AvailabilityPage;
use Acceptance\AvailabilityGui\Zed\Tester\AvailabilityTester;

/**
 * @group Acceptance
 * @group AvailabilityGui
 * @group Zed
 * @group AvailabilityViewCest
 */
class AvailabilityViewCest
{

    /**
     * @param \Acceptance\AvailabilityGui\Zed\Tester\AvailabilityTester $i
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
        $i->assertTableWithDataExists();

        $i->click("//*[@class=\"dataTables_scrollBody\"]/table/tbody/tr/td[7]/a");
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_EDIT_HEADER);

        $i->amOnPage(sprintf(AvailabilityPage::AVAILABILITY_VIEW_URL, AvailabilityPage::AVAILABILITY_ID));

        $i->click('//*[@id="page-wrapper"]/div[2]/div[2]/div/a');
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_LIST_HEADER);
    }

}
