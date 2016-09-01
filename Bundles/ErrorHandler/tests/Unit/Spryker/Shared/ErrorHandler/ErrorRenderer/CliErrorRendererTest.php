<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\ErrorHandler\ErrorRenderer;

use Exception;
use Spryker\Shared\ErrorHandler\ErrorRenderer\CliErrorRenderer;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Error
 * @group ErrorRenderer
 * @group CliErrorRendererTest
 */
class CliErrorRendererTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRenderExceptionShouldReturnString()
    {
        $errorRenderer = new CliErrorRenderer();
        $exception = new Exception('ExceptionMessage');
        $exceptionString = $errorRenderer->render($exception);

        $this->assertInternalType('string', $exceptionString);
    }

}
