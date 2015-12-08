<?php

namespace Unit\SprykerFeature\Zed\Development\Business\CodeStyleFixer\Fixtures\SprykerUseStatementFixer\Input;

use SprykerFeature\One\TwoFive\Three\Four\Five\Foo as FiveFourThreeTwoFiveFoo;
use SprykerFeature\One\TwoFour\Three\Four\Five\Foo as FiveFourThreeFoo;
use SprykerFeature\One\TwoThree\Three\Four\Five\Foo as FiveFourFoo;
use SprykerFeature\One\TwoTwo\Three\Four\Five\Foo as FiveFoo;
use SprykerFeature\One\Two\Three\Four\Five\Foo;
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
