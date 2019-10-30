<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroup\Communication\Controller;

use SprykerTest\Zed\CustomerGroup\CustomerGroupCommunicationTester;
use SprykerTest\Zed\CustomerGroup\PageObject\CustomerGroupListPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerGroup
 * @group Communication
 * @group Controller
 * @group CustomerGroupListCest
 * Add your own group annotations below this line
 */
class CustomerGroupListCest
{
    /**
     * @param \SprykerTest\Zed\CustomerGroup\CustomerGroupCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CustomerGroupCommunicationTester $i)
    {
        $i->amOnPage(CustomerGroupListPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Customers / Customer Groups');
    }

    /**
     * @param \SprykerTest\Zed\CustomerGroup\CustomerGroupCommunicationTester $i
     *
     * @return void
     */
    public function showListOfCustomerGroup(CustomerGroupCommunicationTester $i)
    {
        $i->listDataTable(CustomerGroupListPage::URL . '/index/table');
        $i->seeDataTable();
    }
}
