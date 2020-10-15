<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;

class BrowserHelper extends Module
{
    const PHANTOMJS_BROWSER_NAME = 'phantomjs';

    /**
     * @var \Codeception\Scenario
     */
    protected $scenario;

    /**
     * @param \Codeception\TestInterface $test
     */
    public function _before(\Codeception\TestInterface $test)
    {
        $this->scenario = $test->getScenario();
    }

    /**
     * Disables native HTML5 client-side validation
     *
     * @param string $selector
     *
     * @return void
     */
    public function disableBrowserNativeValidation(string $selector): void
    {
        if ($this->isPhantom()) {
            return;
        }

        /** @var \Codeception\Module\WebDriver $webdriver */
        $webdriver = $this->getModule('WebDriver');
        $webdriver->executeJS(
            <<<EOF
            document.querySelectorAll('{$selector}')
                .forEach(
                    function(element) {
                        element.setAttribute('novalidate','novalidate')
                    }
                );
EOF
        );
    }

    /**
     * Converts date string into a natural-input way for simulating input in modern browsers.
     *
     * @param string $date
     *
     * @return string
     */
    public function adaptDateInputForBrowser(string $date): string
    {
        if (!$this->isPhantom()) {
            $date = implode('', array_reverse(explode('-', $date)));
        }

        return $date;
    }

    /**
     * Checks browser name in configuration
     *
     * @return bool
     */
    protected function isPhantom()
    {
        return ($this->scenario->current('browser') === static::PHANTOMJS_BROWSER_NAME);
    }
}
