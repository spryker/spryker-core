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
 * @group AvailabilityGuiViewCest
 */
class AvailabilityGuiViewCest
{

    /**
     * @param \Acceptance\AvailabilityGui\Zed\Tester\AvailabilityGuiTester $i
     *
     * @return void
     */
    public function testDisplayViewPage(AvailabilityGuiTester $i)
    {
        $i->wantTo('View selected AvailabilityGui item');
        $i->expect('List of all AvailabilityGui items.');

        $i->amOnPage(sprintf(AvailabilityGuiPage::AvailabilityGui_VIEW_URL, AvailabilityGuiPage::AvailabilityGui_ID));

        $i->wait(1);

        $i->see(AvailabilityGuiPage::PAGE_AvailabilityGui_VIEW_HEADER);
        $i->assertTableWithDataExists();

        $i->click("//*[@class=\"dataTables_scrollBody\"]/table/tbody/tr/td[6]/a");
        $i->see(AvailabilityGuiPage::PAGE_AvailabilityGui_EDIT_HEADER);

        $i->amOnPage(sprintf(AvailabilityGuiPage::AvailabilityGui_VIEW_URL, AvailabilityGuiPage::AvailabilityGui_ID));

        $i->click('//*[@id="page-wrapper"]/div[2]/div[2]/div/a');
        $i->see(AvailabilityGuiPage::PAGE_AvailabilityGui_LIST_HEADER);
    }

}
