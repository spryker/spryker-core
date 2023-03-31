<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreGui\Communication\Form;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreGui
 * @group Communication
 * @group Form
 * @group CreateStoreFormTest
 * Add your own group annotations below this line
 */
class CreateStoreFormTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\StoreGui\StoreGuiCommunicationTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const REGEX_NAME_PATTERN = '/^(?!.*_{2})[A-Z][A-Z_]*[A-Z]$/';

    /**
     * @dataProvider regexTestDataProvider
     *
     * @param string $string
     * @param bool $shouldMatch
     *
     * @return void
     */
    public function testStoreNameCanContainOnlyUppercaseLettersSeparatedBySingleUnderscore(string $string, bool $shouldMatch): void
    {
        // Arrange
        $storeNameRegularExpression = static::REGEX_NAME_PATTERN;

        // Act
        $result = preg_match($storeNameRegularExpression, $string);

        // Assert
        $this->assertEquals($shouldMatch, (bool)$result);
    }

    /**
     * @return array<array>
     */
    private function regexTestDataProvider(): array
    {
        return [
            ['A_B', true],
            ['ABC', true],
            ['A_B_C', true],
            ['A_B_C_D', true],
            ['ABCD', true],
            ['A__B', false],
            ['A_B_', false],
            ['_A_B', false],
            ['A__B__C', false],
            ['A__B_C', false],
            ['A_B__C', false],
            ['A__B_C_D', false],
            ['A_B__C_D', false],
            ['A_B_C__D', false],
            ['A_B_C_D_', false],
            ['_A_B_C_D', false],
            ['A_B_1', false],
            ['A_B_*', false],
            ['A_B_?', false],
            ['AB1', false],
            ['AB*', false],
            ['AB?', false],
        ];
    }
}
