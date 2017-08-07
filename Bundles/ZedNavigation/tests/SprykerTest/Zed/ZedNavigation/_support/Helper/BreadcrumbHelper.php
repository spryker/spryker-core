<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Helper;

use Codeception\Module;
use Symfony\Component\VarDumper\VarDumper;

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
        $breadcrumbParts = explode('/', $breadcrumb);
        $breadcrumbParts = array_map('trim', $breadcrumbParts);

        $driver = $this->getWebdriver();
//        $driver->see($breadcrumb, '//ol[@class="breadcrumb"]');

        echo '<pre>' . PHP_EOL . VarDumper::dump($this) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
        $position = 0;

        foreach ($breadcrumbParts as $breadcrumbPart) {
            $driver->see($breadcrumb, sprintf('//ol[@class="breadcrumb"]/li[%s]/a[contains(., "%s")]', $position + 1, $breadcrumbPart));
            $position++;
        }

//        $breadcrumb = str_replace('/', ' ', $breadcrumb);
//
//        $driver = $this->getWebdriver();
//        $driver->see($breadcrumb, '//ol[@class="breadcrumb"]');
    }

    /**
     * @return \Codeception\Module|\Codeception\Module\WebDriver
     */
    private function getWebdriver()
    {
        if ($this->isPresentationSuite) {
            return $this->getModule('WebDriver');
        }

        return $this->getModule('\SprykerTest\Shared\Testify\Helper\ZedBootstrap');
    }

}
