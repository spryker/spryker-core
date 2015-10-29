<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\RemoveFunctionAliasFixer\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replaceFunction()
    {
        $foo = is_int(2);
        $foo = 2/is_writable($foo);
        $foo = explode('', array());
        $foo = fwrite($foo, 'xyz');
        $foo = count(array());
    }

    /**
     * @return void
     */
    public function doNotReplaceFunction()
    {
        $foo = is_int(2);
        $foo = is_writable($foo);
    }

}
