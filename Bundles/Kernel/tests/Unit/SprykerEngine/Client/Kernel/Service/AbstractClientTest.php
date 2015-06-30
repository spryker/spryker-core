<?php

namespace Unit\SprykerEngine\Client\Kernel\Service;

use SprykerEngine\Zed\Kernel\Locator;
use Unit\SprykerEngine\Client\Kernel\Service\Fixtures\KernelFactory;
use Unit\SprykerEngine\Client\Kernel\Service\Fixtures\KernelClient;

/**
 * @group SprykerEngine
 * @group Client
 * @group Kernel
 * @group AbstractClient
 */
class AbstractClientTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractStubMustBeConstructable()
    {
        $abstractStub = new KernelClient(new KernelFactory('Kernel'), Locator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Client\Kernel\Service\Fixtures\KernelClient', $abstractStub);
    }

}
