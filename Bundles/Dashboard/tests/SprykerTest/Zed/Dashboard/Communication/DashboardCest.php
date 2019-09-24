<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Dashboard\Communication;

use SprykerTest\Zed\Dashboard\DashboardCommunicationTester;
use SprykerTest\Zed\Dashboard\PageObject\DashboardPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Dashboard
 * @group Communication
 * @group DashboardCest
 * Add your own group annotations below this line
 */
class DashboardCest
{
    /**
     * @param \SprykerTest\Zed\Dashboard\DashboardCommunicationTester $i
     *
     * @return void
     */
    public function isVisibleBreadcrumb(DashboardCommunicationTester $i)
    {
        $i->amOnPage(DashboardPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Dashboard');
    }

    /**
     * @param \SprykerTest\Zed\Dashboard\DashboardCommunicationTester $i
     *
     * @return void
     */
    public function isVisibleTitle(DashboardCommunicationTester $i)
    {
        $i->amOnPage(DashboardPage::URL);
        $i->canSee(DashboardPage::TITLE, 'h2');
    }
}
