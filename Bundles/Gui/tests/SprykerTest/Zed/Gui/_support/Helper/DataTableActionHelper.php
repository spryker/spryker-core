<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Helper;

use Codeception\Module;

class DataTableActionHelper extends Module
{

    /**
     * @param int $rowPosition
     *
     * @return void
     */
    public function clickDataTableEditButton($rowPosition = 1)
    {
        $this->clickButton('Edit', $rowPosition);
    }

    /**
     * @param int $rowPosition
     *
     * @return void
     */
    public function clickDataTableViewButton($rowPosition = 1)
    {
        $this->clickButton('View', $rowPosition);
    }

    /**
     * @param int $rowPosition
     *
     * @return void
     */
    public function clickDataTableDeleteButton($rowPosition = 1)
    {
        $this->clickButton('Delete', $rowPosition);
    }

    /**
     * @param string $name
     * @param int $rowPosition
     *
     * @return void
     */
    protected function clickButton($name, $rowPosition)
    {
        $webDriver = $this->getWebDriver();
        $selector = sprintf('(//a[contains(., "%s")])[%s]', $name, $rowPosition);

        $webDriver->waitForElement($selector);
        $webDriver->click($selector);
    }

    /**
     * @return \Codeception\Module|\Codeception\Module\WebDriver
     */
    protected function getWebDriver()
    {
        return $this->getModule('WebDriver');
    }

}
