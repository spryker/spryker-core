<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorOpenApi\Plugin;

use Codeception\Test\Unit;
use LogicException;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\DocumentationGeneratorOpenApiContentGeneratorStrategyPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DocumentationGeneratorOpenApi
 * @group Plugin
 * @group DocumentationGeneratorOpenApiContentGeneratorPluginTest
 * Add your own group annotations below this line
 */
class DocumentationGeneratorOpenApiContentGeneratorPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TMP_DIR_NAME = 'DocumentationGeneratorOpenApiContentGeneratorPluginTest';

    /**
     * @var string
     */
    protected const TMP_FILE_NAME = 'generated.yaml.example';

    /**
     * @return void
     */
    public function testCallGenerateContentIsValid(): void
    {
        //Arrange
        $formattedData = [
            'openapi' => '3.0.1',
            'info' => [
                'title' => 'Spryker Glue API specification',
                'description' => '',
                'termsOfService' => 'https://support.spryker.com/',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'Spryker',
                    'url' => 'https://support.spryker.com/',
                    'email' => 'support@spryker.com',
                ],
                'license' => [
                    'name' => 'MIT',
                ],
            ],
            'paths' => [],
        ];
        $exampleFilePath = codecept_data_dir() . static::TMP_DIR_NAME . DIRECTORY_SEPARATOR . static::TMP_FILE_NAME;
        $plugin = new DocumentationGeneratorOpenApiContentGeneratorStrategyPlugin();

        //Act
        $content = $plugin->generateContent($formattedData);

        //Assert
        $this->assertEquals(file_get_contents($exampleFilePath), $content);
    }

    /**
     * @return void
     */
    public function testCallGenerateLogicException(): void
    {
        //Arrange
        $formattedData = [
            'openapi' => '3.0.1',
            'info' => [
                'title' => 'Spryker Glue API specification',
                'description' => '',
                'termsOfService' => 'https://support.spryker.com/',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'Spryker',
                    'url' => 'https://support.spryker.com/',
                    'email' => 'support@spryker.com',
                ],
                'license' => [
                    'name' => 'MIT',
                ],
            ],
        ];
        $plugin = new DocumentationGeneratorOpenApiContentGeneratorStrategyPlugin();

        //Assert
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('OpenApi is missing required property: paths');

        //Act
        $plugin->generateContent($formattedData);
    }
}
