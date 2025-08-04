<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorOpenApi;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorOpenApi\DynamicEntityOpenApiSchemaFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Plugin
 * @group DocumentationGeneratorOpenApi
 * @group DynamicEntityOpenApiSchemaFormatterPluginTest
 * Add your own group annotations below this line
 */
class DynamicEntityOpenApiSchemaFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFormatFormatsWhenDynamicEntityConfigurationsNotDefined(): void
    {
        // Arrange
        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();

        $plugin = new DynamicEntityOpenApiSchemaFormatterPlugin();
        $plugin->setFactory($this->tester->getFactory());

        // Act
        $formattedData = $plugin->format([], $apiApplicationSchemaContextTransfer);

        // Assert
        $this->assertIsArray($formattedData);
        $this->assertSame([], $formattedData);
    }

    /**
     * @return void
     */
    public function testFormatFormatsWhenDynamicEntityConfigurationsNotValid(): void
    {
        // Arrange
        $pathGetMethodBuilderMock = $this->getMockBuilder(PathMethodBuilderInterface::class)->getMock();
        $pathGetMethodBuilderMock->method('buildPathData')->willReturn($this->getMockedGetPathData());

        $pathPostMethodBuilderMock = $this->getMockBuilder(PathMethodBuilderInterface::class)->getMock();
        $pathPostMethodBuilderMock->method('buildPathData')->willReturn($this->getMockedPostPathData());

        $this->tester->mockFactoryMethod('getPathMethodBuilders', [
            $pathGetMethodBuilderMock,
            $pathPostMethodBuilderMock,
        ]);

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();

        $apiApplicationSchemaContextTransfer->setDynamicEntityConfigurations(new ArrayObject([
            new DynamicEntityConfigurationTransfer(),
        ]));

        $plugin = new DynamicEntityOpenApiSchemaFormatterPlugin();
        $plugin->setFactory($this->tester->getFactory());

        // Act
        $formattedData = $plugin->format($this->getInitFormattedData(), $apiApplicationSchemaContextTransfer);

        // Assert
        $this->assertIsArray($formattedData);
        $this->assertEquals($this->getExpectedFormattedData(), $formattedData);
    }

    /**
     * @return void
     */
    public function testFormatFormatsWhenDynamicEntityConfigurationsValid(): void
    {
        // Arrange
        $plugin = new DynamicEntityOpenApiSchemaFormatterPlugin();
        $plugin->setFactory($this->tester->getFactory());

        // Act
        $formattedData = $plugin->format($this->getInitFormattedData(), $this->createApiApplicationSchemaContextTransfer());

        // Assert
        $this->assertIsArray($formattedData);
        $expectedPathData = $this->getExpectedPathData();

        foreach ($expectedPathData as $paths) {
            foreach ($paths as $path => $methods) {
                foreach ($methods as $method => $data) {
                    $formatedPathMethodData = $formattedData['paths'][$path][$method];

                    if (isset($data['responses'])) {
                        $expectedResponses = $data['responses'];
                        $formattedResponses = $formatedPathMethodData['responses'];

                        foreach ($expectedResponses as $responseName => $expectedResponse) {
                            $this->assertEquals($expectedResponse, $formattedResponses[$responseName], sprintf('[%s] %s %s responses do not match.', $method, $responseName, $path));
                        }
                    }

                    $this->assertEquals($data, $formatedPathMethodData, sprintf('[%s] %s does not match the expected result.', $method, $path));
                }
            }
        }
    }

    /**
     * @return array<mixed>
     */
    protected function getExpectedPathData(): array
    {
        return require codecept_data_dir() . 'expectedFormatterPluginFormattedFullData.php';
    }

    /**
     * @return array<mixed>
     */
    protected function getInitFormattedData(): array
    {
        return [
            'paths' => [
                '/collection' => [
                    'get' => [],
                    'post' => [],
                    'put' => [],
                    'patch' => [],
                ],
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function getExpectedFormattedData(): array
    {
        return require codecept_data_dir() . 'expectedFormatterPluginFormattedData.php';
    }

    /**
     * @return array<mixed>
     */
    protected function getMockedGetPathData(): array
    {
        return [
            'paths' => [
                '/collection/{id}' => [
                    'get' => [],
                ],
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function getMockedPostPathData(): array
    {
        return [
            'paths' => [
                '/collection' => [
                    'post' => [
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    protected function createApiApplicationSchemaContextTransfer(): ApiApplicationSchemaContextTransfer
    {
        $dynamicEntityDefinitionTransfer = new DynamicEntityDefinitionTransfer();
        $dynamicEntityDefinitionTransfer->setIdentifier('id');
        $dynamicEntityDefinitionTransfer->setFieldDefinitions(new ArrayObject([
            (new DynamicEntityFieldDefinitionTransfer())->setFieldName('table_id')
                ->setFieldVisibleName('id')
                ->setType('integer')
                ->setIsCreatable(true)
                ->setIsEditable(true)
                ->setValidation((new DynamicEntityFieldValidationTransfer())
                    ->setIsRequired(true)
                    ->setMax(100)
                    ->setMin(1)),
            (new DynamicEntityFieldDefinitionTransfer())->setFieldName('table_field_string')
                ->setFieldVisibleName('field_string')
                ->setType('string')
                ->setIsCreatable(true)
                ->setIsEditable(true)
                ->setValidation((new DynamicEntityFieldValidationTransfer())->setIsRequired(true)
                    ->setMaxLength(5)
                    ->setMinLength(1)),
            (new DynamicEntityFieldDefinitionTransfer())->setFieldName('table_field_decimal')
                ->setFieldVisibleName('field_decimal')
                ->setType('string')
                ->setIsCreatable(true)
                ->setIsEditable(true)
                ->setValidation((new DynamicEntityFieldValidationTransfer())->setIsRequired(true)
                    ->setMaxLength(5)
                    ->setMinLength(1)),
            (new DynamicEntityFieldDefinitionTransfer())->setFieldName('table_field_boolean')
                ->setFieldVisibleName('field_boolean')
                ->setType('boolean')
                ->setIsCreatable(true)
                ->setIsEditable(true)
                ->setValidation((new DynamicEntityFieldValidationTransfer())->setIsRequired(true)),
        ]));

        $dynamicEntityConfigurationTransfer = new DynamicEntityConfigurationTransfer();
        $dynamicEntityConfigurationTransfer->setTableAlias('test');
        $dynamicEntityConfigurationTransfer->setDynamicEntityDefinition($dynamicEntityDefinitionTransfer);

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();

        $apiApplicationSchemaContextTransfer->setDynamicEntityConfigurations(new ArrayObject([
            $dynamicEntityConfigurationTransfer,
        ]));

        return $apiApplicationSchemaContextTransfer;
    }
}
