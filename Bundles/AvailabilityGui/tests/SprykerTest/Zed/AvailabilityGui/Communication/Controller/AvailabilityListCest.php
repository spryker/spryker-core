<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityGui\Communication\Controller;

use SprykerTest\Zed\AvailabilityGui\AvailabilityGuiCommunicationTester;
use SprykerTest\Zed\AvailabilityGui\PageObject\AvailabilityPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityGui
 * @group Communication
 * @group Controller
 * @group AvailabilityListCest
 * Add your own group annotations below this line
 */
class AvailabilityListCest
{
    /**
     * @param \SprykerTest\Zed\AvailabilityGui\AvailabilityGuiCommunicationTester $i
     *
     * @return void
     */
    public function testDisplayListPage(AvailabilityGuiCommunicationTester $i)
    {
        $i->wantTo('Open availability list');
        $i->expect('List of all availability items');

        $i->amOnPage(AvailabilityPage::AVAILABILITY_LIST_URL);
        $i->listDataTable(AvailabilityPage::AVAILABILITY_LIST_URL . '/index/availability-abstract-table');

        $i->seeBreadcrumbNavigation('Dashboard / Products / Availability');

        $i->clickDataTableViewButton();
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_VIEW_HEADER);
    }
}
