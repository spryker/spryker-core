<?php

namespace Unit\Spryker\Zed\Development\Business\CodeStyleFixer\Fixtures\SprykerUseStatementFixer\Input;

use Spryker\One\TwoFive\Three\Four\Five\Foo as FiveFourThreeTwoFiveFoo;
use Spryker\One\TwoFour\Three\Four\Five\Foo as FiveFourThreeFoo;
use Spryker\One\TwoThree\Three\Four\Five\Foo as FiveFourFoo;
use Spryker\One\TwoTwo\Three\Four\Five\Foo as FiveFoo;
use Spryker\One\Two\Three\Four\Five\Foo;
use X\Y;

class TestClass3Input
{

    public function replaceFunction()
    {
        new Foo();
        new FiveFoo();
        new FiveFourFoo();
        new FiveFourThreeFoo();
        new FiveFourThreeTwoFiveFoo();
    }

}
