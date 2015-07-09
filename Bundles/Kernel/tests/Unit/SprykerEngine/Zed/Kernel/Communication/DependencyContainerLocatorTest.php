<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Communication\DependencyContainerLocator;

/**
 * @group Kernel
 * @group DependencyContainerLocator
 */
class DependencyContainerLocatorTest extends \PHPUnit_Framework_TestCase
{

    const TEST_BUNDLE_NAME = 'Kernel';
    const WRONG_TEST_BUNDLE_NAME = 'not existing bundle name';

    public function testLocateShouldReturnQueryContainerClassForBundle()
    {
        $queryContainerLocator = new DependencyContainerLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\Factory'
        );
        $queryContainer = $queryContainerLocator->locate(self::TEST_BUNDLE_NAME, Locator::getInstance());

        $this->assertInstanceOf(
            'Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\KernelDependencyContainer',
            $queryContainer
        );
    }

    public function testLocateShouldThrowExceptionIfFactoryInBundleCanNotBeFound()
    {
        $this->setExpectedException('\SprykerEngine\Shared\Kernel\Locator\LocatorException');

        $queryContainerLocator = new DependencyContainerLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Persistence\\Fixtures\\Factory'
        );
        $queryContainer = $queryContainerLocator->locate(self::WRONG_TEST_BUNDLE_NAME, Locator::getInstance());
    }

}
