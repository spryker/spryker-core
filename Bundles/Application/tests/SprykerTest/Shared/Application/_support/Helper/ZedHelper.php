<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

class ZedHelper extends Module
{

    /**
     * @var bool
     */
    private static $alreadyLoggedIn = false;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
         static::$alreadyLoggedIn = false;
    }

    /**
     * @return $this
     */
    public function amZed()
    {
        $url = Config::hasKey(ApplicationConstants::BASE_URL_ZED)
            ? Config::get(ApplicationConstants::BASE_URL_ZED)
            // @deprecated This is just for backward compatibility
            : Config::get(ApplicationConstants::HOST_ZED_GUI);

        $this->getWebDriver()->_reconfigure(['url' => $url]);

        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return void
     */
    public function amLoggedInUser($username = 'admin@spryker.com', $password = 'change123')
    {
        $i = $this->getWebDriver();

        if (static::$alreadyLoggedIn) {
            return;
        }

        $i->amOnPage('/auth/login');

        $i->fillField('#auth_username', $username);
        $i->fillField('#auth_password', $password);
        $i->click('Login');

        static::$alreadyLoggedIn = true;
    }

    /**
     * @return \Codeception\Module\WebDriver|\Codeception\Module
     */
    protected function getWebDriver()
    {
        return $this->getModule('WebDriver');
    }

}
