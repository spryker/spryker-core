<?php

namespace Unit\SprykerFeature\Zed\Development\Business\CodeStyleFixer\Fixtures\RemoveWrongWhitespaceFixer\Input;

use SprykerEngine\Zed\Foo;
use Pyz\Zed\Foo\Bar\Baz;
use X\Y;

class TestClass1Input extends \Pyz\Zed\Foo\Bar\Baz
{

    public function replaceFunction()
    {
        new Foo($x);
        new Baz($x);
    }

    public function replaceFunctionB()
    {
        new Foo($x);
    }

    public function replaceNotYetFunction()
    {
        //TODO: Baz::x();
        \Pyz\Zed\Foo\Bar\Baz::x();
    }

    public function doNotReplaceFunction()
    {
        return new \DateTime\Foo();
    }

}
