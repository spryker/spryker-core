<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorApi\Generator;

use Codeception\Test\Unit;
use Spryker\Glue\DocumentationGeneratorApi\Dependency\Client\DocumentationGeneratorApiToStorageClientInterface;
use Spryker\Glue\DocumentationGeneratorApi\Dependency\Service\DocumentationGenerationApiToUtilEncodingServiceInterface;
use Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiDependencyProvider;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DocumentationGeneratorApi
 * @group Generator
 * @group DocumentationGeneratorTest
 * Add your own group annotations below this line
 */
class DocumentationGeneratorTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_FILE_NAME = 'spryker_test_api.schema.yml';

    /**
     * @var string
     */
    protected $testOutputFilePath;

    /**
     * @var \SprykerTest\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->testOutputFilePath = codecept_output_dir() . static::TEST_FILE_NAME;
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        if (file_exists($this->testOutputFilePath)) {
            unlink($this->testOutputFilePath);
        }
    }

    /**
     * @return void
     */
    public function testGenerateDocumentationCreatesFileSuccessfully(): void
    {
        //Arrange
        $apiApplicationProviderPluginInterfaceMock = $this->createMock(ApiApplicationProviderPluginInterface::class);
        $apiApplicationProviderPluginInterfaceMock->expects($this->exactly(3))
            ->method('getName')
            ->willReturn('fakeApp');
        $this->tester->setDependency(
            DocumentationGeneratorApiDependencyProvider::PLUGINS_API_APPLICATION_PROVIDER,
            [
                $apiApplicationProviderPluginInterfaceMock,
            ],
        );

        $this->tester->mockConfigMethod('getGeneratedFullFileName', $this->testOutputFilePath);

        $contentGeneratorStrategyPluginMock = $this->createMock(ContentGeneratorStrategyPluginInterface::class);
        $contentGeneratorStrategyPluginMock->expects($this->once())
            ->method('generateContent')
            ->willReturn('fakeContent');
        $storageClientMock = $this->createMock(DocumentationGeneratorApiToStorageClientInterface::class);
        $utilEncodingService = $this->createMock(DocumentationGenerationApiToUtilEncodingServiceInterface::class);
        $utilEncodingService->method('encodeJson')->willReturn('{}');

        $this->tester->setDependency(
            DocumentationGeneratorApiDependencyProvider::PLUGIN_CONTENT_GENERATOR_STRATEGY,
            $contentGeneratorStrategyPluginMock,
        );
        $this->tester->setDependency(
            DocumentationGeneratorApiDependencyProvider::CLIENT_STORAGE,
            $storageClientMock,
        );
        $this->tester->setDependency(
            DocumentationGeneratorApiDependencyProvider::SERVICE_UTIL_ENCODING,
            $utilEncodingService,
        );

        //Act
        $this->tester->getFactory()->createDocumentationGenerator()->generateDocumentation();

        //Assert
        $this->assertFileExists($this->testOutputFilePath);
    }
}
