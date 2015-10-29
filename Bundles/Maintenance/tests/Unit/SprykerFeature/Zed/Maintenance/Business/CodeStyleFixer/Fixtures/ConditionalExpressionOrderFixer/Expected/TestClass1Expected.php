<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\ConditionalExpressionOrderFixer\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replace() {
        if ($foo === null) {
        }
        $foo = 2/($foo === 2);
        if ($foo === true) {
        }
        if ($foo > 2) {
        }
        if ($foo < 2) {
        }
    }

    /**
     * @return void
     */
    public function replaceNotYet()
    {
        $foo = false;
        $foo = 2 == $foo;
        $foo = 2 === $foo;
        if (null === $foo && false === $this->foo()) {
        }
        if (2 <= $this->foo()) {
        }
        if (2 >= $this->foo()) {
        }
    }

    /**
     * @return void
     */
    public function doNotReplace()
    {
        $foo = false;
        $foo = $foo == 2;
        $foo = $foo === 2;
        if ($foo === true) {
        }
        if ($foo === null && $this->foo() === false) {
        }
    }

}
