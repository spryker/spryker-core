<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace UtilText\Module;

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
     * @apram $fail
     *
     * @param \Codeception\TestCase $test
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
