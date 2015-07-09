<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Business\FacadeLocator;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group Kernel
 * @group Business
 * @group AbstractFacade
 */
class AbstractFacadeTest extends \PHPUnit_Framework_TestCase
{

    const BUNDLE_NAME = 'Kernel';
    const WRONG_BUNDLE_NAME = 'not existing bundle name';

    public function testLocateShouldThrowExceptionIfFactoryCanNotBeFound()
    {
        $this->setExpectedException('\SprykerEngine\Shared\Kernel\Locator\LocatorException');

        $facadeLocator = new FacadeLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Business\\Fixtures\\Factory'
        );
        $facade = $facadeLocator->locate(self::WRONG_BUNDLE_NAME, Locator::getInstance());
    }

    public function testCreateInstanceShouldInjectDependencyContainerIfOneExists()
    {
        $facadeLocator = new FacadeLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Business\\Fixtures\\Factory'
        );
        $facade = $facadeLocator->locate(self::BUNDLE_NAME, Locator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Business\Fixtures\KernelFacade', $facade);
    }

}
