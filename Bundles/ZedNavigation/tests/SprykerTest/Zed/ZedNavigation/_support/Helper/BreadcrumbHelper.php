<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Helper;

use Codeception\Exception\ElementNotFound;
use Codeception\Module;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use SprykerTest\Shared\Testify\Helper\ZedBootstrap;
use SprykerTest\Zed\Application\Helper\ApplicationHelper;

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
        $actor = $settings['actor'] ?? $settings['class_name'];
        if (preg_match('/CommunicationTester/', $actor)) {
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
        $this->checkBreadcrumbNavigation($breadcrumb);
    }

    /**
     * @return \Codeception\Module\WebDriver|\SprykerTest\Zed\Application\Helper\ApplicationHelper|\SprykerTest\Shared\Testify\Helper\ZedBootstrap
     */
    private function getDriver()
    {
        if ($this->isPresentationSuite) {
            /** @var \Codeception\Module\WebDriver $webDriver */
            $webDriver = $this->getModule('WebDriver');

            return $webDriver;
        }

        if ($this->hasModule('\\' . ApplicationHelper::class)) {
            /** @var \SprykerTest\Zed\Application\Helper\ApplicationHelper $applicationHelper */
            $applicationHelper = $this->getModule('\\' . ApplicationHelper::class);

            return $applicationHelper;
        }

        /** @var \SprykerTest\Shared\Testify\Helper\ZedBootstrap $zedBootstrap */
        $zedBootstrap = $this->getModule('\\' . ZedBootstrap::class);

        return $zedBootstrap;
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

        try {
            if ($this->isPresentationSuite) {
                $driver->waitForElement('//spryker-breadcrumbs');
            } else {
                $driver->seeElement('//spryker-breadcrumbs');
            }
        } catch (ElementNotFound | NoSuchElementException | TimeoutException $exception) {
            $this->checkBreadcrumbNavigationNative($breadcrumbParts);

            return;
        }

        $breadcrumbAttribute = $driver->grabAttributeFrom('//spryker-breadcrumbs', 'breadcrumbs');
        $driver->assertNotNull($breadcrumbAttribute);
        $decodedBreadcrumbAttribute = json_decode($breadcrumbAttribute, true);
        $driver->assertTrue(is_array($decodedBreadcrumbAttribute));

        foreach ($breadcrumbParts as $key => $breadcrumbPart) {
            $driver->assertTrue(array_key_exists($key, $decodedBreadcrumbAttribute));
            $driver->assertSame($decodedBreadcrumbAttribute[$key]['label'], $breadcrumbPart);
        }
    }

    /**
     * @param array $breadcrumbParts
     *
     * @return void
     */
    protected function checkBreadcrumbNavigationNative(array $breadcrumbParts): void
    {
        $driver = $this->getDriver();
        $position = 0;

        foreach ($breadcrumbParts as $breadcrumbPart) {
            $driver->see($breadcrumbPart, sprintf('//ol[@class="breadcrumb"]/li[%s]', $position + 1));
            $position++;
        }
    }
}
