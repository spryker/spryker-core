<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityGui\Presentation;

use SprykerTest\Zed\AvailabilityGui\AvailabilityGuiPresentationTester;
use SprykerTest\Zed\AvailabilityGui\PageObject\AvailabilityPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityGui
 * @group Presentation
 * @group AvailabilityListCest
 * Add your own group annotations below this line
 */
class AvailabilityListCest
{

    /**
     * @param \SprykerTest\Zed\AvailabilityGui\AvailabilityGuiPresentationTester $i
     *
     * @return void
     */
    public function testDisplayListPage(AvailabilityGuiPresentationTester $i)
    {
        $i->wantTo('Open availability list');
        $i->expect('List of all availability items');

        $i->amOnPage(AvailabilityPage::AVAILABILITY_LIST_URL);

        $i->seeBreadcrumbNavigation('Dashboard / Products / Availability');

        $i->wait(1);

        $i->see(AvailabilityPage::PAGE_AVAILABILITY_LIST_HEADER);
        $i->assertTableWithDataExists();

        $i->clickViewButton();
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_VIEW_HEADER);
    }

}
