<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\ConditionalExpressionOrderFixer\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replace() {
        if (null === $foo) {
        }
        $foo = 2/(2 === $foo);
        if (true === $foo) {
        }
        if (2 < $foo) {
        }
        if (2 > $foo) {
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
