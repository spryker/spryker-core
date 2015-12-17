<?php

namespace Unit\Spryker\Client\Kernel;

use Unit\Spryker\Client\Kernel\Fixtures\KernelClient;

/**
 * @group Spryker
 * @group Client
 * @group Kernel
 * @group AbstractClient
 */
class AbstractClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAbstractStubMustBeConstructable()
    {
        $abstractStub = new KernelClient();

        $this->assertInstanceOf('Unit\Spryker\Client\Kernel\Fixtures\KernelClient', $abstractStub);
    }

}
