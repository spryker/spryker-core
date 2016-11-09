<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Transfer\Module;

use Codeception\Module;
use Codeception\TestCase;

class Functional extends Module
{

    /**
     * @param \Codeception\TestCase $test
     *
     * @return void
     */
    public function _after(TestCase $test)
    {
        parent::_after($test);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @param \Codeception\TestCase $test
     * @param bool $fail
     *
     * @return void
     */
    public function _failed(TestCase $test, $fail)
    {
        parent::_failed($test, $fail);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

}
