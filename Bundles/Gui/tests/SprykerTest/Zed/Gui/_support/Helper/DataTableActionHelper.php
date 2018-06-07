<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Helper;

use Codeception\Module;
use SprykerTest\Shared\Testify\Helper\ZedBootstrap;

class DataTableActionHelper extends Module
{
    /**
     * @var bool
     */
    protected $isPresentationSuite = true;

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
        $className = $settings['class_name'];
        if (preg_match('/CommunicationTester/', $className)) {
            $this->isPresentationSuite = false;
        }
    }

    /**
     * @param int $rowPosition
     * @param null|string $gridId
     *
     * @return void
     */
    public function clickDataTableEditButton($rowPosition = 1, $gridId = null)
    {
        $this->clickButton('Edit', $rowPosition, $gridId);
    }

    /**
     * @param int $rowPosition
     * @param null|string $gridId
     *
     * @return void
     */
    public function clickDataTableViewButton($rowPosition = 1, $gridId = null)
    {
        $this->clickButton('View', $rowPosition, $gridId);
    }

    /**
     * @param int $rowPosition
     * @param null|string $gridId
     *
     * @return void
     */
    public function clickDataTableDeleteButton($rowPosition = 1, $gridId = null)
    {
        $this->clickButton('Delete', $rowPosition, $gridId);
    }

    /**
     * @param string $buttonName
     * @param string $linkName
     * @param int $rowPosition
     *
     * @return void
     */
    public function clickDataTableLinkInDropDownOfButton($buttonName, $linkName, $rowPosition = 1)
    {
        $this->clickButton($buttonName, $rowPosition);
        $this->getDriver()->click(sprintf(
            '(//tr[@role="row"]//button[contains(., "%s")])[%s]/following::ul[1]//a[contains(., "%s")]',
            $buttonName,
            $rowPosition,
            $linkName
        ));
    }

    /**
     * @param string $name
     * @param int $rowPosition
     * @param null|string $gridId
     *
     * @return void
     */
    public function clickDataTableButton($name, $rowPosition = 1, $gridId = null)
    {
        $this->clickButton($name, $rowPosition, $gridId);
    }

    /**
     * @param string $name
     * @param int $rowPosition
     * @param null|string $gridId
     *
     * @return void
     */
    protected function clickButton($name, $rowPosition, $gridId = null)
    {
        $driver = $this->getDriver();

        $selector = sprintf('(//tr[@role="row"]//a[contains(., "%1$s")] | //button[contains(., "%1$s")])[%2$d]', $name, $rowPosition);

        if ($gridId) {
            $selector = sprintf('(//div[@id="%3$s"]//tr[@role="row"]//a[contains(., "%1$s")] | //button[contains(., "%1$s")])[%2$d]', $name, $rowPosition, $gridId);
        }

        if (method_exists($driver, 'waitForElementVisible')) {
            $driver->waitForElementVisible($selector);
        }

        $driver->click($selector);
    }

    /**
     * @return \Codeception\Module|\Codeception\Module\WebDriver|\Codeception\Lib\Framework
     */
    protected function getDriver()
    {
        if ($this->isPresentationSuite) {
            return $this->getModule('WebDriver');
        }

        return $this->getModule('\\' . ZedBootstrap::class);
    }
}
