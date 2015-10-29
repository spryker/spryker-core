<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\MethodArgumentDefaultValueFixer\Fixtures\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replaceFunction()
    {
        $foo = (int)2;
        $foo = 2/(bool)$foo;
    }

    /**
     * @return void
     */
    public function doNotReplaceFunction($foo)
    {
        $foo = (int)2;
        $foo = 2/(bool)$foo;
    }

}
