<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ProductApi\Module;

use Acceptance\Auth\Login\Zed\PageObject\LoginPage;
use Codeception\TestCase;
use Exception;
use Propel\Runtime\Propel;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

class Zed extends Infrastructure
{

    /**
     * @var bool
     */
    private static $alreadyLoggedIn = false;

    /**
     * @param \Codeception\TestCase $test
     *
     * @throws \Exception
     *
     * @return void
     */
    public function _before(TestCase $test)
    {
        parent::_before($test);

        $process = $this->runTestSetup('--restore');

        if ($process->getExitCode() != 0) {
            throw new Exception('An error in data restore occured: ' . $process->getErrorOutput());
        }
    }

    /**
     * @param \Codeception\TestCase $test
     *
     * @return void
     */
    public function _after(TestCase $test)
    {
         Propel::closeConnections();
         static::$alreadyLoggedIn = false;
    }

    /**
     * @return $this
     */
    public function amZed()
    {
        $url = Config::get(ApplicationConstants::HOST_ZED_GUI);

        $this->getWebDriver()->_reconfigure(['url' => $url]);

        return $this;
    }

    /**
     * @return \Codeception\Module\WebDriver
     */
    protected function getWebDriver()
    {
        return $this->getModule('WebDriver');
    }

}
