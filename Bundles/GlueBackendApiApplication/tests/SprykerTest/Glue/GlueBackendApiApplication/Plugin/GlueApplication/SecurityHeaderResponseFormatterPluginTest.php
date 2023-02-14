<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\SecurityHeaderResponseFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group Plugin
 * @group GlueApplication
 * @group SecurityHeaderResponseFormatterPluginTest
 * Add your own group annotations below this line
 */
class SecurityHeaderResponseFormatterPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_ORIGIN = 'Access-Control-Allow-Origin';

    /**
     * @var \SprykerTest\Glue\GlueBackendApiApplication\GlueBackendApiApplicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMataHaveAccessControlAllowOriginHeaderAsAsterisk(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCorsAllowOrigin',
            '*',
        );

        $plugin = new SecurityHeaderResponseFormatterPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        $glueResponseTransfer = new GlueResponseTransfer();
        $glueRequestTransfer = new GlueRequestTransfer();

        // Act
        $glueResponseTransfer = $plugin->format($glueResponseTransfer, $glueRequestTransfer);
        $metaArray = $glueResponseTransfer->getMeta();

        // Assert
        $this->assertArrayHasKey(static::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN, $metaArray);
        $this->assertSame('*', $metaArray[static::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN]);
    }

    /**
     * @return void
     */
    public function testMataHaveAccessControlAllowOriginHeaderAsNull(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCorsAllowOrigin',
            'null',
        );

        $plugin = new SecurityHeaderResponseFormatterPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        $glueResponseTransfer = new GlueResponseTransfer();
        $glueRequestTransfer = new GlueRequestTransfer();

        // Act
        $glueResponseTransfer = $plugin->format($glueResponseTransfer, $glueRequestTransfer);
        $metaArray = $glueResponseTransfer->getMeta();

        // Assert
        $this->assertArrayHasKey(static::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN, $metaArray);
        $this->assertSame('null', $metaArray[static::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN]);
    }

    /**
     * @return void
     */
    public function testMataHaveAccessControlAllowOriginHeaderAsUrl(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCorsAllowOrigin',
            'https://spryker.local',
        );

        $plugin = new SecurityHeaderResponseFormatterPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        $glueResponseTransfer = new GlueResponseTransfer();
        $glueRequestTransfer = new GlueRequestTransfer();

        // Act
        $glueResponseTransfer = $plugin->format($glueResponseTransfer, $glueRequestTransfer);
        $metaArray = $glueResponseTransfer->getMeta();

        // Assert
        $this->assertArrayHasKey(static::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN, $metaArray);
        $this->assertSame('https://spryker.local', $metaArray[static::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN]);
    }
}
