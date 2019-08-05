<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Communication\From\DataProvider;

use Codeception\Test\Unit;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuRegex;
use Symfony\Component\Validator\Validation;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Communication
 * @group From
 * @group DataProvider
 * @group SkuRegexTest
 * Add your own group annotations below this line
 */
class SkuRegexTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductManagement\ProductManagementCommunicationTester
     */
    protected $tester;

    /**
     * @dataProvider getSkuValidationTestData
     *
     * @param string $sku
     * @param int $violationsExpectedCount
     *
     * @return void
     */
    public function testSkuValidation(string $sku, int $violationsExpectedCount): void
    {
        // Assign
        $constraint = new SkuRegex();
        $validator = Validation::createValidator();

        // Act
        $violations = $validator->validate($sku, $constraint);

        // Assert
        $this->assertCount($violationsExpectedCount, $violations);
    }

    /**
     * @return array
     */
    public function getSkuValidationTestData(): array
    {
        return [
            ['abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_.', 0],
            ['a-1', 0],
            ['a-1-1', 0],
            ['b_2', 0],
            ['b_2_2', 0],
            ['c.3', 0],
            ['c.3.3', 0],
            ['d 66', 1],
        ];
    }
}
