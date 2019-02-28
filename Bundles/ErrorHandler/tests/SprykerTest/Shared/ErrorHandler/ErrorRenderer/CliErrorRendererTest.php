<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ErrorHandler\ErrorRenderer;

use Codeception\Test\Unit;
use Exception;
use Spryker\Shared\ErrorHandler\ErrorRenderer\CliErrorRenderer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group ErrorHandler
 * @group ErrorRenderer
 * @group CliErrorRendererTest
 * Add your own group annotations below this line
 */
class CliErrorRendererTest extends Unit
{
    /**
     * @return void
     */
    public function testRenderExceptionShouldReturnString()
    {
        $errorRenderer = new CliErrorRenderer();
        $exception = new Exception('ExceptionMessage');
        $exceptionString = $errorRenderer->render($exception);

        $this->assertIsString($exceptionString);
    }
}
