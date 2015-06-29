<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Sdk\Kernel;

use SprykerEngine\Sdk\Kernel\SdkLocator;
use Unit\SprykerEngine\Sdk\Kernel\Fixtures\KernelSdkLocator;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 * @group FacadeLocator
 */
class SdkLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testLocateFacadeShouldReturnFacadeOfGivenBundle()
    {
        $facadeLocator = new SdkLocator(
            '\\Unit\\SprykerEngine\\Sdk\\{{bundle}}{{store}}\\Fixtures\\KernelFactory'
        );
        $facade = $facadeLocator->locate('Kernel', KernelSdkLocator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Sdk\Kernel\Fixtures\KernelSdk', $facade);
    }
}
