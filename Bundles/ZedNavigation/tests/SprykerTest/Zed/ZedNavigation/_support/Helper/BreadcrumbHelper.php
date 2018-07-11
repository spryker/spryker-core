<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Helper;

use Codeception\Module;
use SprykerTest\Shared\Testify\Helper\ZedBootstrap;

class BreadcrumbHelper extends Module
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
     * @param string $breadcrumb
     *
     * @return void
     */
    public function seeBreadcrumbNavigation($breadcrumb)
    {
        if ($this->isPresentationSuite) {
            $this->checkWithWebdriver($breadcrumb);
        }

        if (!$this->isPresentationSuite) {
            $this->checkWithFramework($breadcrumb);
        }
    }

    /**
     * @return \Codeception\Module|\Codeception\Module\WebDriver|\SprykerTest\Shared\Testify\Helper\ZedBootstrap
     */
    private function getDriver()
    {
        if ($this->isPresentationSuite) {
            return $this->getModule('WebDriver');
        }

        return $this->getModule('\\' . ZedBootstrap::class);
    }

    /**
     * @param string $breadcrumb
     *
     * @return void
     */
    private function checkWithWebdriver($breadcrumb)
    {
        $breadcrumb = str_replace('/', ' ', $breadcrumb);

        $driver = $this->getDriver();
        $driver->see($breadcrumb, '//ol[@class="breadcrumb"]');
    }

    /**
     * @param string $breadcrumb
     *
     * @return void
     */
    private function checkWithFramework($breadcrumb)
    {
        $breadcrumbParts = explode('/', $breadcrumb);
        $breadcrumbParts = array_map('trim', $breadcrumbParts);

        $driver = $this->getDriver();
        $position = 0;

        foreach ($breadcrumbParts as $breadcrumbPart) {
            $driver->see($breadcrumbPart, sprintf('//ol[@class="breadcrumb"]/li[%s]', $position + 1));
            $position++;
        }
    }
}
