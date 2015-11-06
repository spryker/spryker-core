<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\NoInlineAssignment\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replace() {
        $foo = true;
        if ($foo) {
        }
        $foo = $this->foo($x);
        if ($foo) {
        }
    }

    /**
     * @return void
     */
    public function replaceNotYet()
    {
        $foo = 2/($foo = 2);

        if (!($stats = $this->getResource()->getStats())) {
        }
    }

    /**
     * @return void
     */
    public function doNotReplace()
    {
        if ($foo = false || $foo = $this->foo()) {
        }
    }

}
