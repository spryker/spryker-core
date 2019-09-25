<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Codeception\Test\Unit;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Generator
 * @group RestApiDocumentationPathGeneratorTest
 * Add your own group annotations below this line
 */
class RestApiDocumentationPathGeneratorTest extends Unit
{
    protected const METHOD_PATCH = 'patch';
    protected const TEST_PATH_WITH_ID = '/test-path/{test-resource-id}';
    protected const TEST_PATH = '/test-path';
    protected const RESPONSE_CODE_OK = 200;
    protected const RESPONSE_CODE_CREATED = 201;
    protected const RESPONSE_CODE_ACCEPTED = 202;

    /**
     * @var \SprykerTest\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\PathGeneratorInterface
     */
    protected $pathGenerator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->pathGenerator = (new DocumentationGeneratorRestApiTestFactory())->createOpenApiSpecificationPathGenerator();
    }

    /**
     * @return void
     */
    public function testAddGetPathShouldGenerateValidGetMethodDataForPath(): void
    {
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForGetMethod();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->tester->getResponseSchemaDataTransfer(static::RESPONSE_CODE_OK);
        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::TEST_PATH, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedGetPathData(), $paths[static::TEST_PATH]);
    }

    /**
     * @return void
     */
    public function testAddPostPath(): void
    {
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForPostMethod();
        $requestSchemaDataTransfer = $this->tester->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->tester->getResponseSchemaDataTransfer(static::RESPONSE_CODE_CREATED);
        $this->pathGenerator->addPostPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::TEST_PATH, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedPostPathData(), $paths[static::TEST_PATH]);
    }

    /**
     * @return void
     */
    public function testAddPatchPath(): void
    {
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForPatchMethod();
        $requestSchemaDataTransfer = $this->tester->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->tester->getResponseSchemaDataTransfer(static::RESPONSE_CODE_ACCEPTED);
        $this->pathGenerator->addPatchPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::TEST_PATH_WITH_ID, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedPatchPathData(), $paths[static::TEST_PATH_WITH_ID]);
    }

    /**
     * @return void
     */
    public function testAddPatchPathWithoutPassingResponseTransferShouldGenerateValidPatchMethodDataForPath(): void
    {
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForPatchMethod();
        $requestSchemaDataTransfer = $this->tester->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $this->pathGenerator->addPatchPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, null);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::TEST_PATH_WITH_ID, $paths);
        $this->assertArrayHasKey(static::METHOD_PATCH, $paths[static::TEST_PATH_WITH_ID]);
        $this->assertArrayHasKey('responses', $paths[static::TEST_PATH_WITH_ID][static::METHOD_PATCH]);
        $this->assertArrayNotHasKey('content', $paths[static::TEST_PATH_WITH_ID][static::METHOD_PATCH]['responses']);
    }

    /**
     * @return void
     */
    public function testAddDeletePath(): void
    {
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForDeleteMethod();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $this->pathGenerator->addDeletePath($pathMethodDataTransfer, $errorSchemaDataTransfer);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::TEST_PATH_WITH_ID, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedDeletePathData(), $paths[static::TEST_PATH_WITH_ID]);
    }
}
