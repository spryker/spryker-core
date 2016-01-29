<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\SprykerUseStatementFixer\Input;

class TestClass2Input extends \Pyz\Zed\Foo\Bar\Baz
{

    public function replaceFunction()
    {
        new \Pyz\Zed\Foo\Bar\Baz($x);
        new \Spryker\Zed\X\Y\Baz($x);
    }

    public function replaceFunctionB()
    {
        new \Spryker\Zed\X\Y\Baz($x);
        new \Pyz\Zed\Foo\Bar\Baz($x);
    }

    public function replaceFunctionC(\Spryker\Zed\Form\SomeForm $form)
    {
        new\Spryker\Zed\Foo();
    }

    public function doNotReplaceFunction()
    {
        return new \DateTime\Foo();
    }

}
