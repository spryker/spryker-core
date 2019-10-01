<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilText\Model\Filter;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\Model\Filter\SeparatorToCamelCase;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilText
 * @group Model
 * @group Filter
 * @group SeparatorToCamelCaseTest
 * Add your own group annotations below this line
 */
class SeparatorToCamelCaseTest extends Unit
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['camelCase', '-', 'camel-case', false],
            ['camelCase', '_', 'camel_case', false],
            ['camelCase', '\'', 'camel\'case', false],
            ['camelCase', '@', 'camel@case', false],
            ['camelCase', '$1', 'camel$1case', false],
            ['camelCase', 'asd', 'camelasdcase', false],
            ['CamelCase', '-', 'camel-case', true],
            ['CamelCase', '_', 'camel_case', true],
            ['CamelCase', '\'', 'camel\'case', true],
            ['CamelCase', '@', 'camel@case', true],
            ['CamelCase', '$1', 'camel$1case', true],
            ['CamelCase', 'asd', 'camelasdcase', true],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $expected
     * @param string $separator
     * @param string $string
     * @param bool $upperCase
     *
     * @return void
     */
    public function testWithDifferentSeparator($expected, $separator, $string, $upperCase)
    {
        $filter = new SeparatorToCamelCase();
        $this->assertEquals($expected, $filter->filter($string, $separator, $upperCase));
    }
}
