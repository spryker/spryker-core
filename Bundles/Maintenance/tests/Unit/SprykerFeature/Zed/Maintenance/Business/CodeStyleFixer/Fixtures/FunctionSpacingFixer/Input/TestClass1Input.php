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
        $x['e'] = array('foo', function () {
        });
    }
    /**
     * @return void
     */
    public function cFunction()
    {
    }
}
