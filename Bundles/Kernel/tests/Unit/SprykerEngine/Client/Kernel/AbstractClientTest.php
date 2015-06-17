<?php

namespace Unit\SprykerEngine\Client\Kernel;

use SprykerEngine\Client\Kernel\ClientLocator;
use Unit\SprykerEngine\Client\Kernel\Fixtures\KernelClientLocator;

/**
 * @group Kernel
 * @group Business
 * @group AbstractFacade
 */
class AbstractClientTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstanceShouldInjectDependencyContainerIfOneExists()
    {
        $clientLocator = new ClientLocator(
            '\\Unit\\SprykerEngine\\Client\\{{bundle}}{{store}}\\Fixtures\\KernelFactory'
        );
        $client = $clientLocator->locate('Kernel', KernelClientLocator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Client\Kernel\Fixtures\KernelClient', $client);
    }
}
