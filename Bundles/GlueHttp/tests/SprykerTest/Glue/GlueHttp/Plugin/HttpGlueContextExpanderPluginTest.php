<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueHttp\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueHttp\Plugin\GlueContext\HttpGlueContextExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueHttp
 * @group Plugin
 * @group HttpGlueContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class HttpGlueContextExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueHttp\GlueHttpTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testHttpGlueContextExpanderPlugin(): void
    {
        //Arrange
        $expectedPath = '/foo';
        $_SERVER['REQUEST_URI'] = 'https://bar.com' . $expectedPath . '#baz?param1=value1';

        //Act
        $httpGlueContextExpanderPlugin = new HttpGlueContextExpanderPlugin();
        $glueContext = $httpGlueContextExpanderPlugin->expand(new GlueApiContextTransfer());

        // Assert
        $this->assertSame($expectedPath, $glueContext->getPath());
    }
}
