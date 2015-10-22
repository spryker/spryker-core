<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\MethodArgumentDefaultValueFixer\Fixtures\Input;

class TestClass1Input
{

    public function aFunction($foo = null, $bar = null)
    {

    }

    public function bFunction($foo = null, $bar)
    {

    }

    public function cFunction($foo = false, $bar = 'bar', $baz)
    {

    }

    public function dFunction($foo = false, $bar, $baz)
    {

    }

    public function eFunction($foo = PHP_EOL, $bar, $baz = null, $x)
    {

    }

}
