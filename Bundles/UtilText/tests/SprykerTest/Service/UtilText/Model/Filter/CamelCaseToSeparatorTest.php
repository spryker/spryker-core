<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilText\Model\Filter;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\Model\Filter\CamelCaseToSeparator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilText
 * @group Model
 * @group Filter
 * @group CamelCaseToSeparatorTest
 * Add your own group annotations below this line
 */
class CamelCaseToSeparatorTest extends Unit
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
     * @param string $camelCase
     * @param string $separator
     * @param string $expected
     *
     * @return void
     */
    public function testWithDifferentSeparator($camelCase, $separator, $expected)
    {
        $filter = new CamelCaseToSeparator();
        $this->assertEquals($expected, $filter->filter($camelCase, $separator));
    }
}
