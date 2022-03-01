<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\JsonResponseEncoderPlugin;
use stdClass;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group JsonResponseEncoderPluginTest
 * Add your own group annotations below this line
 */
class JsonResponseEncoderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptedFormatJson(): void
    {
        //Act
        $plugin = new JsonResponseEncoderPlugin();
        $result = $plugin->getAcceptedFormats();

        //Assert
        $this->assertSame(['application/json'], $result);
    }

    /**
     * @return void
     */
    public function testAcceptTypes(): void
    {
        //Act
        $plugin = new JsonResponseEncoderPlugin();
        $result = $plugin->accepts($this->acceptedTypesDataProvider(), new GlueRequestTransfer());

        //Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testUsesEncodingService(): void
    {
        //Act
        $jsonResponseEncoderPlugin = new JsonResponseEncoderPlugin();
        $glueResponseTransfer = $jsonResponseEncoderPlugin->encode($this->acceptedTypesDataProvider(), new GlueResponseTransfer());
        $result = $glueResponseTransfer->getContent();

        //Assert
        $this->assertIsString($result);
        $this->assertStringContainsString('string', $result);
        $this->assertStringContainsString('array_value', $result);
    }

    /**
     * @return array
     */
    protected function acceptedTypesDataProvider(): array
    {
        return [
            ['string'],
            [100],
            [1.2],
            [['array_key' => 'array_value']],
            [null],
            [(new stdClass())],
        ];
    }
}
