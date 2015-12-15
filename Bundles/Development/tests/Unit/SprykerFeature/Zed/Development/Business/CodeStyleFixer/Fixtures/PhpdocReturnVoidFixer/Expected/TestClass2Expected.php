<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\PhpdocReturnVoidFixer\Input;

interface TestClass2Input
{

    public function voidFunction();

    /**
     * @param mixed $foo
     *
     * @return int
     */
    public function nonVoidFunction($foo);

}
