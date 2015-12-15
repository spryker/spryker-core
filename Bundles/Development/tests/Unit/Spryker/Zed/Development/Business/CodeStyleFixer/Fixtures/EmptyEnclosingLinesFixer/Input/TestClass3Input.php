<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\EmptyEnclosingLinesFixer\Input;

interface TestClass3Input
{
    public function aFunction();

    public function bFunction();

    /**
     * @return void
     */
    public function cFunction();
}
