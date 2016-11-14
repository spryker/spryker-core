<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Application\Module;

use Acceptance\Auth\Login\Zed\PageObject\LoginPage;
use Codeception\Module;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

class Zed extends Module
{

    const I_WAS_HERE = 'I_WAS_HERE';

    /**
     * @return $this
     */
    public function amZed()
    {
        $url = 'http://' . Config::get(ApplicationConstants::HOST_ZED_GUI);

        $this->getWebDriver()->_reconfigure(['url' => $url]);

        return $this;
    }

    /**
     * Set cookie after login. When cookie given do not login in again.
     * This currently does not work.
     *
     * @param string $username
     * @param string $password
     *
     * @return void
     */
    public function amLoggedInUser($username = 'admin@spryker.com', $password = 'change123')
    {
        $i = $this->getWebDriver();

//        $cookie = $i->grabCookie(self::I_WAS_HERE);
//        if ($cookie) {
//            return;
//        }

        $i->amOnPage(LoginPage::URL);

        $i->fillField(LoginPage::SELECTOR_USERNAME_FIELD, $username);
        $i->fillField(LoginPage::SELECTOR_PASSWORD_FIELD, $password);
        $i->click(LoginPage::SELECTOR_SUBMIT_BUTTON);

//        $i->setCookie(self::I_WAS_HERE, true);
//        $i->saveSessionSnapshot('LoginZed');
    }

    /**
     * @return \Codeception\Module\WebDriver
     */
    protected function getWebDriver()
    {
        return $this->getModule('WebDriver');
    }

}
