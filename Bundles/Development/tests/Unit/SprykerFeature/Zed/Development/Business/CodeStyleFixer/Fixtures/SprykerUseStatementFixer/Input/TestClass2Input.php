<?php

namespace Unit\SprykerFeature\Zed\Development\Business\CodeStyleFixer\Fixtures\SprykerUseStatementFixer\Input;

class TestClass2Input extends \Pyz\Zed\Foo\Bar\Baz
{

    public function replaceFunction()
    {
        new \Pyz\Zed\Foo\Bar\Baz($x);
        new \SprykerFeature\Zed\X\Y\Baz($x);
    }

    public function replaceFunctionB()
    {
        new \SprykerFeature\Zed\X\Y\Baz($x);
        new \Pyz\Zed\Foo\Bar\Baz($x);
    }

    public function replaceFunctionC()
    {
        new\SprykerEngine\Zed\Foo();
    }

    public function doNotReplaceFunction()
    {
        return new \DateTime\Foo();
    }

}
