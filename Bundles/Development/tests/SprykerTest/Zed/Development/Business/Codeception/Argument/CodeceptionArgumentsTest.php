<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\Codeception\Argument;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group Codeception
 * @group Argument
 * @group CodeceptionArgumentsTest
 * Add your own group annotations below this line
 */
class CodeceptionArgumentsTest extends Unit
{
    /**
     * @dataProvider argumentsDataProvider
     *
     * @param string $argumentName
     * @param string[] $argumentValues
     * @param string[] $result
     *
     * @return void
     */
    public function testAddArgument(string $argumentName, array $argumentValues, array $result): void
    {
        $codeceptionArgument = new CodeceptionArguments();

        $codeceptionArgument->addArgument($argumentName, $argumentValues);
        $this->assertSame($result, $codeceptionArgument->getArguments());
    }

    /**
     * @return array
     */
    public function argumentsDataProvider(): array
    {
        return [
            'without value' => ['group', [], ['group']],
            'with value' => ['group', [1], ['group', 1]],
            'multiple value' => ['group', [1, 2, 3], ['group', 1, 'group', 2, 'group', 3]],
        ];
    }
}
