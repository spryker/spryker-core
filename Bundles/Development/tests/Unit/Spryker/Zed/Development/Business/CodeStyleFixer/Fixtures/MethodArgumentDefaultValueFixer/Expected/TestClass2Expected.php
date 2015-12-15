<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\MethodArgumentDefaultValueFixer\Input;

interface TestClass2Input
{

    public function aFunction($foo = null, $bar = null);

    public function bFunction($foo, $bar);

    public function cFunction($foo, $bar, $baz);

    public function dFunction($foo, $bar, $baz);

}
