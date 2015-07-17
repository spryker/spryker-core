<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\Library\Filter;

use SprykerFeature\Shared\Library\Filter\SeparatorToCamelCaseFilter;

/**
 * @group Filter
 */
class SeparatorToCamelCaseFilterTest extends \PHPUnit_Framework_TestCase
{

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
     */
    public function testWithDifferentSeperator($expected, $seperator, $string, $upperCase)
    {
        $filter = new SeparatorToCamelCaseFilter($seperator, $upperCase);
        $this->assertEquals($expected, $filter->filter($string));
    }

}
