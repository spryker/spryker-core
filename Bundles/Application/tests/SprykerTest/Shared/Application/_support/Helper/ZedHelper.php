<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Exception;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;

class ZedHelper extends Module
{
    protected const LOGOUT_LINK_SELECTOR = "(//a[contains(@href,'/auth/logout')])[2]";
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
        if ($this->seeElement(static::LOGOUT_LINK_SELECTOR)) {
            $tester = $this->getWebDriver();
            $tester->click(static::LOGOUT_LINK_SELECTOR);
        }

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

        $host = Config::get(TestifyConstants::WEB_DRIVER_HOST, '0.0.0.0');

        $this->getWebDriver()->_reconfigure(['url' => $url, 'host' => $host]);

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
        $tester = $this->getWebDriver();

        if (static::$alreadyLoggedIn) {
            return;
        }

        $tester->amOnPage('/auth/login');

        $tester->fillField('#auth_username', $username);
        $tester->fillField('#auth_password', $password);
        $tester->click('Login');

        $tester->waitForElementVisible('#side-menu');

        static::$alreadyLoggedIn = true;
    }

    /**
     * @return \Codeception\Module\WebDriver|\Codeception\Module
     */
    protected function getWebDriver()
    {
        return $this->getModule('WebDriver');
    }

    /**
     * @param string $selector
     *
     * @return bool
     */
    protected function seeElement(string $selector): bool
    {
        try {
            $this->getWebDriver()->seeElement($selector);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
