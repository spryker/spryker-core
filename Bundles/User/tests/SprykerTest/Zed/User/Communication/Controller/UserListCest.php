<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Communication\Controller;

use SprykerTest\Zed\User\PageObject\UserListPage;
use SprykerTest\Zed\User\UserCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Communication
 * @group Controller
 * @group UserListCest
 * Add your own group annotations below this line
 */
class UserListCest
{
    /**
     * @param \SprykerTest\Zed\User\UserCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(UserCommunicationTester $i)
    {
        $i->amOnPage(UserListPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / User');
    }
}
