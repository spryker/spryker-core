<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\SprykerUseStatementFixer\Input;

use X\Y;

class TestClass3Input
{

    public function replaceFunction()
    {
        new \Spryker\One\Two\Three\Four\Five\Foo();
        new \Spryker\One\TwoTwo\Three\Four\Five\Foo();
        new \Spryker\One\TwoThree\Three\Four\Five\Foo();
        new \Spryker\One\TwoFour\Three\Four\Five\Foo();
        new \Spryker\One\TwoFive\Three\Four\Five\Foo();
    }

}
