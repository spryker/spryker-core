<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Customer\Customer\Zed;

use Acceptance\Customer\Customer\Zed\PageObject\CustomerListPage;
use Acceptance\Customer\Customer\Zed\Tester\CustomerTester;

/**
 * @group Acceptance
 * @group Customer
 * @group Customer
 * @group Zed
 * @group CustomerListCest
 */
class CustomerListCest
{

    /**
     * @param \Acceptance\Customer\Customer\Zed\Tester\CustomerTester $i
     *
     * @return void
     */
    public function showListOfCustomer(CustomerTester $i)
    {
        $i->amOnPage(CustomerListPage::URL);
        $i->seeElement(CustomerListPage::SELECTOR_TABLE);
    }

}
