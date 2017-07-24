<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroup\Presentation;

use SprykerTest\Zed\CustomerGroup\PageObject\CustomerGroupEditPage;
use SprykerTest\Zed\CustomerGroup\PageObject\CustomerGroupViewPage;
use SprykerTest\Zed\CustomerGroup\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CustomerGroup
 * @group Presentation
 * @group CustomerGroupViewCest
 * Add your own group annotations below this line
 */
class CustomerGroupEditCest
{

    /**
     * @param \SprykerTest\Zed\CustomerGroup\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $customerGroupTransfer = $i->haveCustomerGroup();
        $i->amOnPage(CustomerGroupEditPage::buildUrl($customerGroupTransfer->getIdCustomerGroup()));

        $i->seeBreadcrumbNavigation('Dashboard / Customers / Customer Groups / Edit customer group');
    }

}
