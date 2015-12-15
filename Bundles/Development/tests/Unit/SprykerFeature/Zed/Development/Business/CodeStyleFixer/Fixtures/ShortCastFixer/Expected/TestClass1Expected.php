<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\ShortCastFixer\Input;

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
    public function doNotReplaceFunction()
    {
        $foo = (int)2;
        $foo = 2/(bool)$foo;
    }

}
