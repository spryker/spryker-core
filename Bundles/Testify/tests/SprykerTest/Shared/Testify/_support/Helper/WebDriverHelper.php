<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Event\SuiteEvent;
use Codeception\Events;
use Codeception\Extension\Phantoman;
use Codeception\Module\WebDriver;

class WebDriverHelper extends Phantoman
{
    protected const MODULE_NAME_WEBDRIVER = 'WebDriver';

    protected const KEY_REMOTE_ENABLE = 'remote-enable';
    protected const KEY_HOST = 'host';
    protected const KEY_PORT = 'port';
    protected const KEY_BROWSER = 'browser';

    protected const DEFAULT_HOST = '0.0.0.0';
    protected const DEFAULT_PORT = 4444;
    protected const DEFAULT_BROWSER = 'chrome';

    /**
     * @var string[]
     */
    public static $events = [
        Events::SUITE_INIT => 'suiteInit',
        Events::SUITE_BEFORE => 'configureWebDriverModule',
    ];

    /**
     * @param \Codeception\Event\SuiteEvent $e
     *
     * @return void
     */
    public function suiteInit(SuiteEvent $e): void
    {
        if (!$this->isRemoteEnabled()) {
            parent::suiteInit($e);
        }
    }

    /**
     * @return void
     */
    public function configureWebDriverModule(): void
    {
        if (!$this->hasModule(static::MODULE_NAME_WEBDRIVER)) {
            return;
        }

        $this->getWebDriver()->_reconfigure(
            $this->getWebDriverConfig()
        );
    }

    /**
     * @return string[]
     */
    protected function getWebDriverConfig(): array
    {
        $webdriverConfig = [];

        $webdriverConfig[static::KEY_HOST] = $this->config[static::KEY_HOST] ?? static::DEFAULT_HOST;
        $webdriverConfig[static::KEY_PORT] = $this->config[static::KEY_PORT] ?? static::DEFAULT_PORT;
        $webdriverConfig[static::KEY_BROWSER] = $this->config[static::KEY_BROWSER] ?? static::DEFAULT_BROWSER;

        return $webdriverConfig;
    }

    /**
     * @return \Codeception\Module\WebDriver
     */
    protected function getWebDriver(): WebDriver
    {
        return $this->getModule(static::MODULE_NAME_WEBDRIVER);
    }

    /**
     * @return bool
     */
    protected function isRemoteEnabled(): bool
    {
        $isRemoteEnabled = $this->config[static::KEY_REMOTE_ENABLE] ?? false;

        if ($isRemoteEnabled === 'false') {
            return false;
        }

        return (bool)$isRemoteEnabled;
    }
}
