<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Library\Filter;

use Spryker\Shared\Library\Filter\CamelCaseToSeparatorFilter;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Library
 * @group Filter
 * @group CamelCaseToSeparatorFilterTest
 */
class CamelCaseToSeparatorFilterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['camelCase', '-', 'camel-case'],
            ['camelCase', '_', 'camel_case'],
            ['camelCase', '\'', 'camel\'case'],
            ['camelCase', '@', 'camel@case'],
            ['camelCase', '$1', 'camel$1case'],
            ['camelCase', 'asd', 'camelasdcase'],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testWithDifferentSeparator($camelCase, $separator, $expected)
    {
        $filter = new CamelCaseToSeparatorFilter($separator);
        $this->assertEquals($expected, $filter->filter($camelCase));
    }

}
