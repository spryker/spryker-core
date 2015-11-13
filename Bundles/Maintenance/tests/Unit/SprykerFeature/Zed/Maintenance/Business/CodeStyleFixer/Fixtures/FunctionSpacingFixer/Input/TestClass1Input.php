<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\FunctionSpacingFixer\Input;

class TestClass1Input
{
    public function aFunction()
    {
        $x = function ($y) use ($z) {
        };
    }
    public function bFunction()
    {
    }
    /**
     * @return void
     */
    public function cFunction()
    {
    }
}
