<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;
use PHPUnit\Framework\Assert;

class AssertHelper extends Module
{
    /**
     * @param string $expected
     * @param array $collection
     * @param string $message
     *
     * @return void
     */
    public function assertAllInstanceOf(string $expected, array $collection, string $message = ''): void
    {
        foreach ($collection as $item) {
            Assert::assertInstanceOf($expected, $item, $message);
        }
    }
}
