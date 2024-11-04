<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;

trait AssertArraySubsetTrait
{
    /**
     * Asserts that a subset of an array matches the expected values, including nested arrays.
     *
     * @param array $expected The subset array that should be present in the actual array.
     * @param array $actual The actual array to check against.
     * @param string $message An optional message to display on failure.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function assertArraySubset(array $expected, array $actual, string $message = ''): void
    {
        foreach ($expected as $key => $value) {
            if (!array_key_exists($key, $actual)) {
                throw new ExpectationFailedException(
                    $message ?: "Key '$key' is missing in the actual array.",
                );
            }

            if (is_array($value)) {
                $this->assertArraySubset($value, $actual[$key], $message);

                continue;
            }

            Assert::assertEquals($value, $actual[$key], $message);
        }
    }
}
