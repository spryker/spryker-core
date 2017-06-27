<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Application\Module;

use Acceptance\Auth\Login\Zed\PageObject\LoginPage;
use Codeception\TestInterface;
use Exception;
use Propel\Runtime\Propel;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

/**
 * @deprecated Please use `SprykerTest\Shared\Application\Helper\ZedHelper` instead.
 */
class Zed extends Infrastructure
{

    /**
     * @var bool
     */
    private static $alreadyLoggedIn = false;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @throws \Exception
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        $process = $this->runTestSetup('--restore');

        if ($process->getExitCode() != 0) {
            throw new Exception('An error in data restore occured: ' . $process->getErrorOutput());
        }
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        Propel::closeConnections();
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

        if (static::$alreadyLoggedIn) {
            return;
        }

        $i->amOnPage(LoginPage::URL);

        $i->fillField(LoginPage::SELECTOR_USERNAME_FIELD, $username);
        $i->fillField(LoginPage::SELECTOR_PASSWORD_FIELD, $password);
        $i->click(LoginPage::SELECTOR_SUBMIT_BUTTON);

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
