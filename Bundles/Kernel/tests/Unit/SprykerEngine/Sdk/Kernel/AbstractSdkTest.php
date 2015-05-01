<?php

namespace Unit\SprykerEngine\Sdk\Kernel;

use SprykerEngine\Sdk\Kernel\SdkLocator;
use Unit\SprykerEngine\Sdk\Kernel\Fixtures\KernelSdkLocator;

/**
 * @group Kernel
 * @group Business
 * @group AbstractFacade
 */
class AbstractSdkTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstanceShouldInjectDependencyContainerIfOneExists()
    {
        $sdkLocator = new SdkLocator(
            '\\Unit\\SprykerEngine\\Sdk\\{{bundle}}{{store}}\\Fixtures\\KernelFactory'
        );
        $sdk = $sdkLocator->locate('Kernel', KernelSdkLocator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Sdk\Kernel\Fixtures\KernelSdk', $sdk);
    }
}
