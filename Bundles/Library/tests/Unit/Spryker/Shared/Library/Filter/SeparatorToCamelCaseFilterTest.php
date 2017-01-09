<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Library\Filter;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Library\Filter\SeparatorToCamelCaseFilter;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Library
 * @group Filter
 * @group SeparatorToCamelCaseFilterTest
 */
class SeparatorToCamelCaseFilterTest extends PHPUnit_Framework_TestCase
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
     * @return void
     */
    public function testWithDifferentSeparator($expected, $separator, $string, $upperCase)
    {
        $filter = new SeparatorToCamelCaseFilter($separator, $upperCase);
        $this->assertEquals($expected, $filter->filter($string));
    }

}
