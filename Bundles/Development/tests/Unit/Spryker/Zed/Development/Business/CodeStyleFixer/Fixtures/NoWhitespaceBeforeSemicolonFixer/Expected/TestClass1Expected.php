<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\NoWhitespaceBeforeSemicolonFixer\Input;

class TestClass1Input
{

    public function replaceFunction()
    {
        $x = $y + $z;
    }

    public function replaceFunctionB()
    {
        $x = $this->foo
            ->bar;
    }

    public function replaceFunctionC()
    {
        return $foo;
    }

    public function doNotReplaceFunction()
    {
        return;
    }

}
