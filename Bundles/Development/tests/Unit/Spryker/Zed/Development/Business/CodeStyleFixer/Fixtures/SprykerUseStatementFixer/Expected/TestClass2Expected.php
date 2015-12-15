<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\SprykerUseStatementFixer\Input;

use Spryker\Zed\Foo;
use Spryker\Zed\X\Y\Baz as YBaz;
use Pyz\Zed\Foo\Bar\Baz;

class TestClass2Input extends \Pyz\Zed\Foo\Bar\Baz
{

    public function replaceFunction()
    {
        new Baz($x);
        new YBaz($x);
    }

    public function replaceFunctionB()
    {
        new YBaz($x);
        new Baz($x);
    }

    public function replaceFunctionC()
    {
        new Foo();
    }

    public function doNotReplaceFunction()
    {
        return new \DateTime\Foo();
    }

}
