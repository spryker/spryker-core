<?php

namespace Unit\SprykerEngine\Client\Kernel;

use SprykerEngine\Client\Kernel\ClientLocator;
use Unit\SprykerEngine\Client\Kernel\Fixtures\KernelClientLocator;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 * @group FacadeLocator
 */
class ClientLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testLocateFacadeShouldReturnFacadeOfGivenBundle()
    {
        $facadeLocator = new ClientLocator(
            '\\Unit\\SprykerEngine\\Client\\{{bundle}}{{store}}\\Fixtures\\KernelFactory'
        );
        $facade = $facadeLocator->locate('Kernel', KernelClientLocator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Client\Kernel\Fixtures\KernelClient', $facade);
    }
}
