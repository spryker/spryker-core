<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Communication\Controller;

use SprykerTest\Zed\Customer\CustomerCommunicationTester;
use SprykerTest\Zed\Customer\PageObject\CustomerIndexPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Communication
 * @group Controller
 * @group CustomerIndexCest
 * Add your own group annotations below this line
 */
class CustomerIndexCest
{
    /**
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function breadCrumbIsVisible(CustomerCommunicationTester $i)
    {
        $i->amOnPage(CustomerIndexPage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Customers / Customers');
    }
}
