<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Persistence;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainerLocator;

/**
 * @group Kernel
 * @group Locator
 */
class QueryContainerLocatorTest extends \PHPUnit_Framework_TestCase
{

    const BUNDLE_NAME = 'Kernel';
    const WRONG_BUNDLE_NAME = 'not existing bundle name';

    public function testLocateShouldReturnQueryContainerClassForBundle()
    {
        $queryContainerLocator = new QueryContainerLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Persistence\\Fixtures\\Factory'
        );

        $queryContainer = $queryContainerLocator->locate(self::BUNDLE_NAME, Locator::getInstance());

        $this->assertInstanceOf(
            'Unit\SprykerEngine\Zed\Kernel\Persistence\Fixtures\KernelQueryContainer',
            $queryContainer
        );
    }

    public function testLocateShouldThrowExceptionIfFactoryInBundleCanNotBeFound()
    {
        $this->setExpectedException('\SprykerEngine\Shared\Kernel\Locator\LocatorException');

        $queryContainerLocator = new QueryContainerLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Persistence\\Fixtures\\Factory'
        );
        $queryContainer = $queryContainerLocator->locate(self::WRONG_BUNDLE_NAME, Locator::getInstance());
    }

}
