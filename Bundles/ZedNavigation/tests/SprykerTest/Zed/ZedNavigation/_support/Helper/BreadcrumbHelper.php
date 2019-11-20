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
    public function _beforeSuite($settings = []): void
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
    public function seeBreadcrumbNavigation(string $breadcrumb): void
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
    private function checkWithWebdriver(string $breadcrumb): void
    {
        $this->checkBreadcrumbNavigation($breadcrumb);
    }

    /**
     * @param string $breadcrumb
     *
     * @return void
     */
    private function checkWithFramework(string $breadcrumb): void
    {
        $this->checkBreadcrumbNavigation($breadcrumb);
    }

    /**
     * @param string $breadcrumb
     *
     * @return void
     */
    protected function checkBreadcrumbNavigation(string $breadcrumb): void
    {
        $breadcrumbParts = explode('/', $breadcrumb);
        $breadcrumbParts = array_map('trim', $breadcrumbParts);

        $driver = $this->getDriver();

        $driver->seeElement('//spryker-breadcrumbs');
        $breadcrumbAttribute = $driver->grabAttributeFrom('//spryker-breadcrumbs', 'breadcrumbs');
        $driver->assertNotNull($breadcrumbAttribute);
        $decodedBreadcrumbAttribute = json_decode($breadcrumbAttribute, true);
        $driver->assertTrue(is_array($decodedBreadcrumbAttribute));

        foreach ($breadcrumbParts as $key => $breadcrumbPart) {
            $driver->assertTrue(array_key_exists($key, $decodedBreadcrumbAttribute));
            $driver->assertSame($decodedBreadcrumbAttribute[$key]['label'], $breadcrumbPart);
        }
    }
}
