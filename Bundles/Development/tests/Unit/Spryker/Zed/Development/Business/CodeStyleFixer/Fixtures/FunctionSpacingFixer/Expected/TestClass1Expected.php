<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\FunctionSpacingFixer\Input;

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
