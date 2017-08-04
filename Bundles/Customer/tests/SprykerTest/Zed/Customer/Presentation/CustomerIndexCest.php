<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Presentation;

use SprykerTest\Zed\Customer\CustomerPresentationTester;
use SprykerTest\Zed\Customer\PageObject\CustomerIndexPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Presentation
 * @group CustomerIndexCest
 * Add your own group annotations below this line
 */
class CustomerIndexCest
{

    /**
     * @param \SprykerTest\Zed\Customer\CustomerPresentationTester $i
     *
     * @return void
     */
    public function breadCrumbIsVisible(CustomerPresentationTester $i)
    {
        $i->amLoggedInUser();
        $i->amOnPage(CustomerIndexPage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Customers / Customers');
    }

}
