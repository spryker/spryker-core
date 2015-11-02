<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\MethodArgumentDefaultValueFixer\Input;

class TestClass1Input
{

    public function aFunction($foo = null, $bar = null)
    {
        $bar->setCallback(function () use ($foo) {
            $csvHandle = fopen('php://output', 'w+');
            return $foo;
        });
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

    public function eFunction($foo = PHP_EOL, $bar, \SplFileInfo $baz = null, $x)
    {

    }

}
