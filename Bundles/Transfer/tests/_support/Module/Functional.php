<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Transfer\Module;

use Codeception\Module;
use Codeception\TestInterface;

class Functional extends Module
{

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        parent::_after($test);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @param \Codeception\TestInterface $test
     * @param bool $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail)
    {
        parent::_failed($test, $fail);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

}
