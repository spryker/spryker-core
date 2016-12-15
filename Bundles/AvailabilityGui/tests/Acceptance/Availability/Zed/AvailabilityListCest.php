<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\AvailabilityGui\Zed;

use Acceptance\AvailabilityGui\Zed\PageObject\AvailabilityGuiPage;
use Acceptance\AvailabilityGui\Zed\Tester\AvailabilityGuiTester;

/**
 * @group Acceptance
 * @group AvailabilityGui
 * @group Zed
 * @group AvailabilityGuiListCest
 */
class AvailabilityGuiListCest
{

    /**
     * @param \Acceptance\AvailabilityGui\Zed\Tester\AvailabilityGuiTester $i
     *
     * @return void
     */
    public function testDisplayListPage(AvailabilityGuiTester $i)
    {
        $i->wantTo('Open AvailabilityGui list');
        $i->expect('List of all AvailabilityGui items');

        $i->amOnPage(AvailabilityGuiPage::AvailabilityGui_LIST_URL);

        $i->wait(1);

        $i->see(AvailabilityGuiPage::PAGE_AvailabilityGui_LIST_HEADER);
        $i->assertTableWithDataExists();

        $i->click("//*[@class=\"dataTables_scrollBody\"]/table/tbody/tr/td[6]/a");
        $i->see(AvailabilityGuiPage::PAGE_AvailabilityGui_VIEW_HEADER);
    }

}
