<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\PhpdocReturnSelfFixer\Input;

class TestClass1Input
{

    /**
     * @return $this|null Text
     */
    public function replaceFunction()
    {
    }

    /**
     * @return $this
     */
    public function replaceFunctionB()
    {
    }

    /**
     * @return int|$this
     */
    public function replaceFunctionC()
    {
    }

    /**
     * @return int|$this|bool
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
