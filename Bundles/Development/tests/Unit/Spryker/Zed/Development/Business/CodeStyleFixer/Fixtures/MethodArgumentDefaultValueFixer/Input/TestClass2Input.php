<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\MethodArgumentDefaultValueFixer\Input;

interface TestClass2Input
{

    public function aFunction($foo = null, $bar = null);

    public function bFunction($foo = null, $bar);

    public function cFunction($foo = false, $bar = 'bar', $baz);

    public function dFunction($foo = false, $bar, $baz);

}
