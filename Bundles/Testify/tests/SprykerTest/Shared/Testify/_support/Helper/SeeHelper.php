<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;
use PHPUnit_Framework_Assert;

class SeeHelper extends Module
{

    /**
     * @param string $pattern
     * @param mixed $value
     *
     * @return void
     */
    public function seeMatches($pattern, $value)
    {
        PHPUnit_Framework_Assert::assertRegExp($pattern, $value);
    }

    /**
     * @param string $pattern
     * @param mixed $value
     *
     * @return void
     */
    public function dontSeeMatches($pattern, $value)
    {
        PHPUnit_Framework_Assert::assertNotRegExp($pattern, $value);
    }

}
