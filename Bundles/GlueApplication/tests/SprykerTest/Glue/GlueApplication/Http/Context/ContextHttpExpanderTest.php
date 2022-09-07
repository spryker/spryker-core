<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Http\Context;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueApplication\Http\Context\ContextHttpExpander;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Http
 * @group Context
 * @group ContextHttpExpanderTest
 * Add your own group annotations below this line
 */
class ContextHttpExpanderTest extends Unit
{
    /**
     * @return void
     */
    public function testServerNameAsHost(): void
    {
        //Arrange
        $expectedHost = 'foo.bar';
        $_SERVER['HTTP_HOST'] = '';
        $_SERVER['SERVER_NAME'] = $expectedHost;

        //Act
        $contextExpander = new ContextHttpExpander(Request::createFromGlobals());
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        // Assert
        $this->assertSame($expectedHost, $glueContext->getHost());
    }

    /**
     * @return void
     */
    public function testHttpHostAsHost(): void
    {
        //Arrange
        $expectedHost = 'foo.bar';
        $_SERVER['SERVER_NAME'] = '';
        $_SERVER['HTTP_HOST'] = $expectedHost;

        //Act
        $contextExpander = new ContextHttpExpander(Request::createFromGlobals());
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        // Assert
        $this->assertSame($expectedHost, $glueContext->getHost());
    }

    /**
     * @return void
     */
    public function testHttpMethodAsMethod(): void
    {
        //Arrange
        $expectedMethod = 'FOO';
        $_SERVER['REQUEST_METHOD'] = $expectedMethod;

        //Act
        $contextExpander = new ContextHttpExpander(Request::createFromGlobals());
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        // Assert
        $this->assertSame($expectedMethod, $glueContext->getMethod());
    }

    /**
     * @return void
     */
    public function testHttpMethodAsMethodAlwaysInUppercase(): void
    {
        //Arrange
        $expectedMethod = 'foo';
        $_SERVER['REQUEST_METHOD'] = $expectedMethod;

        //Act
        $contextExpander = new ContextHttpExpander(Request::createFromGlobals());
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        // Assert
        $this->assertSame(strtoupper($expectedMethod), $glueContext->getMethod());
    }

    /**
     * @return void
     */
    public function testPathFromUri(): void
    {
        //Arrange
        $expectedPath = '/foo';
        $_SERVER['REQUEST_URI'] = 'https://bar.com' . $expectedPath . '#baz?param1=value1';

        //Act
        $contextExpander = new ContextHttpExpander(Request::createFromGlobals());
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        // Assert
        $this->assertSame($expectedPath, $glueContext->getPath());
    }
}
