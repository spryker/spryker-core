<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Business\FacadeLocator;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 * @group FacadeLocator
 */
class FacadeLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testLocateFacadeShouldReturnFacadeOfGivenBundle()
    {
        $facadeLocator = new FacadeLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Business\\Fixtures\\Factory'
        );
        $facade = $facadeLocator->locate('Kernel', Locator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Business\Fixtures\KernelFacade', $facade);
    }

}
