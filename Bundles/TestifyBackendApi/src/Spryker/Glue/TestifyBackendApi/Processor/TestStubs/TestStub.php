<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\TestStubs;

use Codeception\Test\Test;

class TestStub extends Test
{
    /**
     * @return void
     */
    public function test(): void
    {
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return 'TestStub';
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'TestStub';
    }
}
