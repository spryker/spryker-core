<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Presentation;

use SprykerTest\Zed\User\PageObject\UserCreatePage;
use SprykerTest\Zed\User\UserPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Presentation
 * @group UserCreateCest
 * Add your own group annotations below this line
 */
class UserCreateCest
{

    /**
     * @param \SprykerTest\Zed\User\UserPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(UserPresentationTester $i)
    {
        $i->amOnPage(UserCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Users Control / User / Create new User');
    }

}
