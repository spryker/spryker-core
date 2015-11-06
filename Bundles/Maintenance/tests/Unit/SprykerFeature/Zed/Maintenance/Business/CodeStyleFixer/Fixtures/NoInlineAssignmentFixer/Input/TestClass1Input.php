<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\Fixtures\NoInlineAssignment\Input;

class TestClass1Input
{

    /**
     * @return void
     */
    public function replace() {
        if ($foo = true) {
        }
        if ($foo = $this->foo($x)) {
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
