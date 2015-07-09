<?php

namespace Unit\SprykerEngine\Client\Kernel\Service;

use SprykerEngine\Client\Kernel\Service\ClientLocator;
use Unit\SprykerEngine\Client\Kernel\Service\Fixtures\KernelClientLocator;

/**
 * @group SprykerEngine
 * @group Client
 * @group Kernel
 * @group ClientLocator
 */
class ClientLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testLocateStubShouldReturnStubOfGivenBundle()
    {
        $locator = new ClientLocator(
            '\\Unit\\SprykerEngine\\Client\\{{bundle}}{{store}}\\Service\\Fixtures\\KernelFactory'
        );
        $located = $locator->locate('Kernel', KernelClientLocator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Client\Kernel\Service\Fixtures\KernelClient', $located);
    }

}
