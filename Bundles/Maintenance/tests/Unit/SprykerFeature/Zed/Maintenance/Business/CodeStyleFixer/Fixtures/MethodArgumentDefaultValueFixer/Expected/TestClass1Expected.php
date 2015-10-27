<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\MethodArgumentDefaultValueFixer\Fixtures\Input;

class TestClass1Input
{

    public function aFunction($foo = null, $bar = null)
    {

    }

    public function bFunction($foo, $bar)
    {

    }

    public function cFunction($foo, $bar, $baz)
    {

    }

    public function dFunction($foo, $bar, $baz)
    {

    }

    public function eFunction($foo, $bar, \SplFileInfo $baz, $x)
    {

    }

}
