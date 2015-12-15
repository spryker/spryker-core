<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\PhpdocReturnVoidFixer\Input;

class TestClass1Input
{

    /**
     * @param mixed $foo
     * @return void
     */
    public function voidFunction($foo)
    {
        $foo = $foo * 2;
    }

    /**
     * @param mixed $foo
     * @return void
     */
    protected static function returnEarlyVoidFunction($foo)
    {
        if ($foo === null) {
            return;
        }
        $foo = function () use ($foo) {
            return $foo;
        };
        return;
    }

    /**
     * @param mixed $foo
     */
    public function nonVoidFunction($foo)
    {
        $foo = function () use ($foo) {
            // Foo
        };
        return $foo;
    }

    /**
     * @param mixed $foo
     */
    public function returnEarlyNonVoidFunction($foo)
    {
        if ($foo === null) {
            return null;
        }
        $foo = $foo * 2;
        return $foo;
    }

    /**
     * @return void
     */
    public function missingDocBlock($foo) {
        $foo = $foo * 2;
    }

    /**
     * @return void
     */
    public function alreadyVoid() {
        $foo = $foo * 2;
    }

}
