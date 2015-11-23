<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\PreferCastOverFunctionFixer\Input;

class TestClass1Input
{

    public function replaceFunction()
    {
        $foo = (float)2;
        $foo = 2 / (int)$foo;
        $foo = (string)$x;
        if ((bool)$bool === false) {
        }
    }

    public function doNotReplaceFunction()
    {
        $foo = intval(2,$x);
        $foo = $this->foo(intval($foo, $bar));
        $this->intval($x);
        new Floatval($foo);
    }

}
