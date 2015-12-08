<?php

namespace Unit\SprykerFeature\Zed\Development\Business\CodeStyleFixer\Fixtures\SprykerUseStatementFixer\Input;

use X\Y;

class TestClass3Input
{

    public function replaceFunction()
    {
        new \SprykerFeature\One\Two\Three\Four\Five\Foo();
        new \SprykerFeature\One\TwoTwo\Three\Four\Five\Foo();
        new \SprykerFeature\One\TwoThree\Three\Four\Five\Foo();
        new \SprykerFeature\One\TwoFour\Three\Four\Five\Foo();
        new \SprykerFeature\One\TwoFive\Three\Four\Five\Foo();
    }

}
