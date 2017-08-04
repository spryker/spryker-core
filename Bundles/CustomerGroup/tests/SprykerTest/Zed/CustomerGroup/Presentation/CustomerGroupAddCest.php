<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroup\Presentation;

use SprykerTest\Zed\CustomerGroup\CustomerGroupPresentationTester;
use SprykerTest\Zed\CustomerGroup\PageObject\CustomerGroupAddPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CustomerGroup
 * @group Presentation
 * @group CustomerGroupAddCest
 * Add your own group annotations below this line
 */
class CustomerGroupAddCest
{

    /**
     * @param \SprykerTest\Zed\CustomerGroup\CustomerGroupPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CustomerGroupPresentationTester $i)
    {
        $i->amOnPage(CustomerGroupAddPage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Customers / Customer Groups / Add a customer group');
    }

}
