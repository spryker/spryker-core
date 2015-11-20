<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\PreferCastOverFunctionFixer\Input;

class TestClass1Input
{

    public function replaceFunction()
    {
        $foo = floatval(2);
        $foo = 2 / intval($foo);
        $foo = strval($x);
        if (boolval($bool) === false) {
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
