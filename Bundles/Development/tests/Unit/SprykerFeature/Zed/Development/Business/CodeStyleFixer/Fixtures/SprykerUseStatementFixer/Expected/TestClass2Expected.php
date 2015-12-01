<?php

namespace Unit\SprykerFeature\Zed\Development\Business\CodeStyleFixer\Fixtures\RemoveWrongWhitespaceFixer\Input;

use SprykerEngine\Zed\Foo;
use Pyz\Zed\Foo\Bar\Baz;

class TestClass2Input extends \Pyz\Zed\Foo\Bar\Baz
{

    public function replaceFunction()
    {
        new Baz($x);
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
