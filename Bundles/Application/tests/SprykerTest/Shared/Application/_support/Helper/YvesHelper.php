<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Helper;

use Codeception\Module;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;

class YvesHelper extends Module
{
    /**
     * @return $this
     */
    public function amYves()
    {
        $url = Config::get(ApplicationConstants::BASE_URL_YVES);
        $host = Config::get(TestifyConstants::WEB_DRIVER_HOST, '0.0.0.0');

        $this->getWebDriver()->_reconfigure(['url' => $url, 'host' => $host]);

        return $this;
    }

    /**
     * @return \Codeception\Module\WebDriver|\Codeception\Module
     */
    protected function getWebDriver()
    {
        return $this->getModule('WebDriver');
    }

    /**
     * @param string $buttonName
     *
     * @return void
     */
    public function clickButton(string $buttonName): void
    {
        $i = $this->getWebDriver();
        $i->click("//*[text()[contains(.,'$buttonName')]]");
    }
}
