<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Auth\Login\Zed\Tester;

use Acceptance\Auth\Login\Zed\PageObject\LoginPage;
use Auth\AcceptanceTester;

class LoginTester extends AcceptanceTester
{

    /**
     * @param string $username
     * @param string $password
     *
     * @return $this
     */
    public function doLogin($username, $password)
    {
        $i = $this;
        $i->amZed();
        $i->amOnPage(LoginPage::URL);
        $i->fillField(LoginPage::SELECTOR_USERNAME_FIELD, $username);
        $i->fillField(LoginPage::SELECTOR_PASSWORD_FIELD, $password);
        $i->click(LoginPage::SELECTOR_SUBMIT_BUTTON);

        return $this;
    }

}
