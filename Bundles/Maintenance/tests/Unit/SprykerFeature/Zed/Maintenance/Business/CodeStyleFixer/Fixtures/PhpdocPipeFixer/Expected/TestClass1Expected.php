<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\PhpdocPipeFixer\Input;

class TestClass1Input
{

    /**
     * @var x|y|z
     */
    protected $var;

    /**
     * @param int|bool $foo
     * @param float|Object|integer $bar
     *
     * @return self|null Text
     */
    public function replaceFunction($foo, $bar)
    {
    }

    /**
     * @param int|bool $foo Foo
     *
     * @return void
     */
    public function doNotReplaceFunction()
    {
    }

}
