<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroup\Communication\Controller;

use SprykerTest\Zed\CustomerGroup\CustomerGroupCommunicationTester;
use SprykerTest\Zed\CustomerGroup\PageObject\CustomerGroupAddPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerGroup
 * @group Communication
 * @group Controller
 * @group CustomerGroupAddCest
 * Add your own group annotations below this line
 */
class CustomerGroupAddCest
{
    /**
     * @param \SprykerTest\Zed\CustomerGroup\CustomerGroupCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CustomerGroupCommunicationTester $i)
    {
        $i->amOnPage(CustomerGroupAddPage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Customers / Customer Groups / Add a customer group');
    }
}
