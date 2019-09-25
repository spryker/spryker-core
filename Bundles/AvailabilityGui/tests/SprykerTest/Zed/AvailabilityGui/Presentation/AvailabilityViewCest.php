<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityGui\Presentation;

use SprykerTest\Zed\AvailabilityGui\AvailabilityGuiPresentationTester;
use SprykerTest\Zed\AvailabilityGui\PageObject\AvailabilityPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityGui
 * @group Presentation
 * @group AvailabilityViewCest
 * Add your own group annotations below this line
 */
class AvailabilityViewCest
{
    /**
     * @param \SprykerTest\Zed\AvailabilityGui\AvailabilityGuiPresentationTester $i
     *
     * @return void
     */
    public function testDisplayViewPage(AvailabilityGuiPresentationTester $i)
    {
        $i->wantTo('View selected availability item');
        $i->expect('List of all availability items.');

        $i->amOnPage(sprintf(
            AvailabilityPage::AVAILABILITY_VIEW_URL,
            AvailabilityPage::AVAILABILITY_ID,
            AvailabilityPage::AVAILABILITY_ID_STORE
        ));

        $i->seeBreadcrumbNavigation('Dashboard / Products / Availability / Product Availability');

        $i->wait(1);

        $i->see(AvailabilityPage::PAGE_AVAILABILITY_VIEW_HEADER);
        $i->assertTableWithDataExists();

        $i->click("//*[@class=\"dataTables_scrollBody\"]/table/tbody/tr/td[8]/a");
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_EDIT_HEADER);

        $i->amOnPage(sprintf(AvailabilityPage::AVAILABILITY_VIEW_URL, AvailabilityPage::AVAILABILITY_ID, AvailabilityPage::AVAILABILITY_ID_STORE));

        $i->click('//*[@id="page-wrapper"]/div[2]/div[2]/div/a');
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_LIST_HEADER);
    }
}
