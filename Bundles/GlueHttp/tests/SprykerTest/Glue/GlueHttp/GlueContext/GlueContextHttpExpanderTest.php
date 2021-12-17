<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueHttp\GlueContext;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueHttp\GlueContext\GlueContextHttpExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueHttp
 * @group GlueContext
 * @group GlueContextHttpExpanderTest
 * Add your own group annotations below this line
 */
class GlueContextHttpExpanderTest extends Unit
{
    /**
     * @return void
     */
    public function testServerNameAsHost(): void
    {
        $expectedHost = 'foo.bar';
        $_SERVER['HTTP_HOST'] = '';
        $_SERVER['SERVER_NAME'] = $expectedHost;

        $contextExpander = new GlueContextHttpExpander();
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        $this->assertSame($expectedHost, $glueContext->getHost());
    }

    /**
     * @return void
     */
    public function testHttpHostAsHost(): void
    {
        $expectedHost = 'foo.bar';
        $_SERVER['SERVER_NAME'] = '';
        $_SERVER['HTTP_HOST'] = $expectedHost;

        $contextExpander = new GlueContextHttpExpander();
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        $this->assertSame($expectedHost, $glueContext->getHost());
    }

    /**
     * @return void
     */
    public function testHttpMethodAsMethod(): void
    {
        $expectedMethod = 'FOO';
        $_SERVER['REQUEST_METHOD'] = $expectedMethod;

        $contextExpander = new GlueContextHttpExpander();
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        $this->assertSame($expectedMethod, $glueContext->getMethod());
    }

    /**
     * @return void
     */
    public function testHttpMethodAsMethodAlwaysInUppercase(): void
    {
        $expectedMethod = 'foo';
        $_SERVER['REQUEST_METHOD'] = $expectedMethod;

        $contextExpander = new GlueContextHttpExpander();
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        $this->assertSame(strtoupper($expectedMethod), $glueContext->getMethod());
    }

    /**
     * @return void
     */
    public function testPathFromUri(): void
    {
        $expectedPath = '/foo';
        $_SERVER['REQUEST_URI'] = 'https://bar.com' . $expectedPath . '#baz?param1=value1';

        $contextExpander = new GlueContextHttpExpander();
        $glueContext = $contextExpander->expand(new GlueApiContextTransfer());

        $this->assertSame($expectedPath, $glueContext->getPath());
    }
}
