<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\NoIsNullFixer\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replaceFunction($foo)
    {
        if (is_null($foo)) {
        }
        $foo = is_null($foo);
        $foo = 2/is_null($this->foo());

        $foo = !is_null($foo);

        $foo = $foo || is_null($foo);

        $foo = (int) is_null($foo);
    }

    /**
     * @return void
     */
    public function doNotReplaceFunction()
    {
        $foo = is_null($x = $this->get());
    }

}
