<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention;

use Codeception\Test\Unit;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\JsonApiResponseFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Plugin
 * @group GlueJsonApiConvention
 * @group JsonApiResponseFormatterPluginTest
 * Add your own group annotations below this line
 */
class JsonApiResponseFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testJsonApiResponseFormatterPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $glueResponseTransfer = $this->tester->createGlueResponseTransfer();

        //Act
        $jsonApiResponseFormatterPlugin = new JsonApiResponseFormatterPlugin();
        $glueResponseTransfer = $jsonApiResponseFormatterPlugin->format($glueResponseTransfer, $glueRequestTransfer);

        // Assert
        $content = $glueResponseTransfer->getContent();
        $this->assertNotNull($content);
        $this->assertIsString($content);
        $this->assertStringContainsString('articles', $content);

        $decodedContent = json_decode($content, true);
        $this->assertIsArray($decodedContent);
        $this->assertArrayHasKey('data', $decodedContent);
        $this->assertArrayHasKey('id', $decodedContent['data']);
        $this->assertSame('1', $decodedContent['data']['id']);
    }
}
