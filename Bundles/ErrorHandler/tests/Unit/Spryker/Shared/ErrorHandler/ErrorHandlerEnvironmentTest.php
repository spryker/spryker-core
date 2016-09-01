<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Error;

use Spryker\Shared\ErrorHandler\ErrorHandlerEnvironment;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Error
 * @group ErrorHandlerEnvironmentTest
 */
class ErrorHandlerEnvironmentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInitializeShouldSetErrorHandler()
    {
        $errorHandlerEnvironment = new ErrorHandlerEnvironment();
        $errorHandlerEnvironment->initialize();
    }

}
