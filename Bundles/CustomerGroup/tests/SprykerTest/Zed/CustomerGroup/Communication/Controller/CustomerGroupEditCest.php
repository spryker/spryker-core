<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroup\Communication\Controllrt;

use SprykerTest\Zed\CustomerGroup\CustomerGroupCommunicationTester;
use SprykerTest\Zed\CustomerGroup\PageObject\CustomerGroupEditPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerGroup
 * @group Communication
 * @group Controllrt
 * @group CustomerGroupEditCest
 * Add your own group annotations below this line
 */
class CustomerGroupEditCest
{
    /**
     * @param \SprykerTest\Zed\CustomerGroup\CustomerGroupCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CustomerGroupCommunicationTester $i)
    {
        $customerGroupTransfer = $i->haveCustomerGroup();
        $i->amOnPage(CustomerGroupEditPage::buildUrl($customerGroupTransfer->getIdCustomerGroup()));

        $i->seeBreadcrumbNavigation('Dashboard / Customers / Customer Groups / Edit customer group');
    }
}
