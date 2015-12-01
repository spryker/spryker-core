<?php

namespace Unit\SprykerFeature\Zed\Development\Business\CodeStyleFixer\Fixtures\RemoveWrongWhitespaceFixer\Input;

use X\Y;

class TestClass1Input extends \Pyz\Zed\Foo\Bar\Baz
{

    public function replaceFunction()
    {
        new \Pyz\Zed\Foo\Bar\Baz($x);
    }

    public function replaceFunctionB()
    {
        \Pyz\Zed\Foo\Bar\Baz::x();
    }

    public function replaceFunctionC()
    {
        new\SprykerEngine\Zed\Foo($x);
    }

    public function doNotReplaceFunction()
    {
        return new \DateTime\Foo();
    }

}
