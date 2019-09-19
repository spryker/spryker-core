<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Kernel;

use Codeception\Test\Unit;
use SprykerTest\Client\Kernel\Fixtures\KernelClient;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Kernel
 * @group AbstractClientTest
 * Add your own group annotations below this line
 */
class AbstractClientTest extends Unit
{
    /**
     * @return void
     */
    public function testAbstractStubMustBeConstructable()
    {
        $abstractStub = new KernelClient();

        $this->assertInstanceOf('SprykerTest\Client\Kernel\Fixtures\KernelClient', $abstractStub);
    }
}
