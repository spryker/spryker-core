<?php

namespace Unit\SprykerFeature\Zed\Development\Business\CodeStyleFixer\Fixtures\RemoveWrongWhitespaceFixer\Input;

use SprykerEngine\Zed\Foo;
use Pyz\Zed\Foo\Bar\Baz;
use X\Y;

class TestClass1Input extends \Pyz\Zed\Foo\Bar\Baz
{

    public function replaceFunction()
    {
        new Baz($x);
    }

    public function replaceFunctionB()
    {
        Baz::x();
    }

    public function replaceFunctionC()
    {
        new Foo($x);
    }

    public function doNotReplaceFunction()
    {
        return new \DateTime\Foo();
    }

}
