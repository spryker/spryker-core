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
    protected const NAME_MODULE_WEBDRIVER = 'WebDriver';

    protected const KEY_REMOTE_ENABLE = 'remote-enable';
    protected const KEY_HOST = 'host';
    protected const KEY_PORT = 'port';
    protected const KEY_BROWSER = 'browser';

    /**
     * @var array
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
            parent::suiteInit($e); //todo: refactoring
        }
    }

    /**
     * @return void
     */
    public function configureWebDriverModule(): void
    {
        if (!$this->hasModule(static::NAME_MODULE_WEBDRIVER)) {
            return;
        }

        $this->getWebDriver()->_reconfigure(
            $this->getWebDriverConfig()
        );
    }

    /**
     * @return array
     */
    protected function getWebDriverConfig(): array
    {
        $webdriverConfig = [];

        $webdriverConfig[static::KEY_HOST] = $this->config[static::KEY_HOST] ?? '0.0.0.0';
        $webdriverConfig[static::KEY_PORT] = $this->config[static::KEY_PORT] ?? 4444;
        $webdriverConfig[static::KEY_BROWSER] = $this->config[static::KEY_BROWSER] ?? 'chrome';

        return $webdriverConfig;
    }

    /**
     * @return \Codeception\Module\WebDriver
     */
    protected function getWebDriver(): WebDriver
    {
        return $this->getModule(static::NAME_MODULE_WEBDRIVER);
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
