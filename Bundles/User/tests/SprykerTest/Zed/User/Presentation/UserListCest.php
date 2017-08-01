<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Presentation;

use SprykerTest\Zed\User\PageObject\UserListPage;
use SprykerTest\Zed\User\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Presentation
 * @group UserListCest
 * Add your own group annotations below this line
 */
class UserListCest
{

    /**
     * @param \SprykerTest\Zed\User\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(UserListPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / User');
    }

}
