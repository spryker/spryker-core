<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\NoIsNullFixer\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replaceFunction($foo)
    {
        if ($foo === null) {
        }
        $foo = $foo === null;
        $foo = 2/is_null($this->foo());

        $foo = $foo !== null;

        $foo = $foo || $foo !== null;

        $foo = (int) ($foo === null);

        if ($foo === null) {
        }
        if ($foo !== null) {
        }

        // We also fix these deprecated ones
        if ($foo === null) {
        }
        if ($foo !== null) {
        }
    }

    /**
     * @return void
     */
    public function doNotReplaceFunction()
    {
        $foo = is_null($x = $this->get());
    }

}
