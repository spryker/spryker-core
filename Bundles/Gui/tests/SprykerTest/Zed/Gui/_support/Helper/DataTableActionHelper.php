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
    public function _beforeSuite($settings = []): void
    {
        $className = $settings['class_name'];
        if (preg_match('/CommunicationTester/', $className)) {
            $this->isPresentationSuite = false;
        }
    }

    /**
     * @param int $rowPosition
     * @param string|null $gridId
     *
     * @return void
     */
    public function clickDataTableEditButton(int $rowPosition = 1, ?string $gridId = null): void
    {
        $this->clickButton('Edit', $rowPosition, $gridId);
    }

    /**
     * @param int $rowPosition
     * @param string|null $gridId
     *
     * @return void
     */
    public function clickDataTableViewButton(int $rowPosition = 1, ?string $gridId = null): void
    {
        $this->clickButton('View', $rowPosition, $gridId);
    }

    /**
     * @param int $rowPosition
     * @param string|null $gridId
     *
     * @return void
     */
    public function clickDataTableDeleteButton(int $rowPosition = 1, ?string $gridId = null): void
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
    public function clickDataTableLinkInDropDownOfButton(string $buttonName, string $linkName, int $rowPosition = 1): void
    {
        $this->clickButton($buttonName, $rowPosition);

        $elementInRoot = sprintf(
            '//body/ul[@class="dropdown-menu"][1]//following::li/a[contains(., "%s")]',
            $linkName
        );

        $elementInList = sprintf(
            '(//table[@class="dataTable"]//button[contains(., "%s")])[%s]/following::ul[1]//a[contains(., "%s")]',
            $buttonName,
            $rowPosition,
            $linkName
        );

        $this->getDriver()->click(sprintf('%s | %s', $elementInRoot, $elementInList));
    }

    /**
     * @param string $name
     * @param int $rowPosition
     * @param string|null $gridId
     *
     * @return void
     */
    public function clickDataTableButton(string $name, int $rowPosition = 1, ?string $gridId = null): void
    {
        $this->clickButton($name, $rowPosition, $gridId);
    }

    /**
     * @param string $name
     * @param int $rowPosition
     * @param string|null $gridId
     *
     * @return void
     */
    protected function clickButton(string $name, int $rowPosition, ?string $gridId = null): void
    {
        $driver = $this->getDriver();

        $selector = sprintf('(//table[@class="dataTable"]//a[contains(., "%1$s")] | //button[contains(., "%1$s")])[%2$d]', $name, $rowPosition);

        if ($gridId) {
            $selector = sprintf('(//div[@id="%3$s"]//table[@class="dataTable"]//a[contains(., "%1$s")] | //button[contains(., "%1$s")])[%2$d]', $name, $rowPosition, $gridId);
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
