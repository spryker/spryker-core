<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Kernel;

use Unit\Spryker\Client\Kernel\Fixtures\KernelClient;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Kernel
 * @group AbstractClientTest
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
