<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\PhpdocReturnSelfFixer\Input;

class TestClass1Input
{

    /**
     * @return self|null Text
     */
    public function replaceFunction()
    {
    }

    /**
     * @return self
     */
    public function replaceFunctionB()
    {
    }

    /**
     * @return int|self
     */
    public function replaceFunctionC()
    {
    }

    /**
     * @return int|self|bool
     */
    public function replaceFunctionD()
    {
    }

    /**
     * @return self Foo $this
     */
    public function doNotReplaceFunction()
    {
    }

}
