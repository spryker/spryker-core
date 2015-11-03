<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\MethodArgumentDefaultValueFixer\Input;

class TestClass1Input
{

    public function aFunction($foo = null, $bar = null)
    {
        // We may not touch those
        $bar->setCallback(function () use ($foo) {
            $csvHandle = fopen('php://output', 'w+');
            return $foo;
        });
    }

    public function bFunction($foo, $bar)
    {
        // We may not touch those
        while ($matches = $this->getMatches($lines[++$i], true)) {
            $items[] = $matches;
        }
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
