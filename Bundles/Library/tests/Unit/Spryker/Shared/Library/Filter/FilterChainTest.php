<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Library\Filter;

use Spryker\Shared\Library\Filter\CamelCaseToSeparatorFilter;
use Spryker\Shared\Library\Filter\FilterChain;

/**
 * @group Filter
 */
class FilterChainTest extends \PHPUnit_Framework_TestCase
{

    public function dataProvider()
    {
        return [
            ['camelCase'],
            ['1_as2'],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testEmptyFilterChainShouldNotChangeString($string)
    {
        $filterChain = new FilterChain();
        $this->assertEquals($string, $filterChain->filter($string));
    }

    /**
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testFilterChainAcceptsAndRunsFilterInterface($string)
    {
        $filter = new CamelCaseToSeparatorFilter('-');
        $filterChain = new FilterChain();
        $filterChain->addFilter($filter);
        $this->assertEquals($filter->filter($string), $filterChain->filter($string));
    }

    /**
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testFilterChainAcceptsAndRunsCallable($string)
    {
        $filter = function ($string) {
            return strtolower($string);
        };
        $filterChain = new FilterChain();
        $filterChain->addFilter($filter);
        $this->assertEquals($filter($string), $filterChain->filter($string));
    }

    /**
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testFilterChainAcceptsAndRunsCallableAndFilterInterface($string)
    {
        $filter = function ($string) {
            return strtolower($string);
        };
        $filter2 = new CamelCaseToSeparatorFilter('-');

        $filterChain = new FilterChain();
        $filterChain->addFilter($filter);
        $filterChain->addFilter($filter2);
        $this->assertEquals($filter2->filter($filter($string)), $filterChain->filter($string));
    }

    /**
     * @dataProvider dataProvider
     * @expectedException \LogicException
     * @expectedExceptionMessage The filter is neither a FilterInterface nor a callable.
     *
     * @return void
     */
    public function testFilterChainNotAcceptsString($string)
    {
        $filterChain = new FilterChain();
        $filterChain->addFilter('string');
        $filterChain->filter('something');
    }

    /**
     * @dataProvider dataProvider
     * @expectedException \LogicException
     * @expectedExceptionMessage The filter is neither a FilterInterface nor a callable.
     *
     * @return void
     */
    public function testFilterChainNotAcceptsObjects($string)
    {
        $filterChain = new FilterChain();
        $filterChain->addFilter(new \stdClass());
        $filterChain->filter('something');
    }

}
