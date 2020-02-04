<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Codeception\Test\Unit;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
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
    use ArraySubsetAsserts;

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
     * @return array
     */
    public function statusCodesAndDescriptionDataProvider(): array
    {
        return [
            [204, 'No Content'],
            [208, 'Already Reported'],
            [226, 'IM Used'],
        ];
    }

    /**
     * @dataProvider statusCodesAndDescriptionDataProvider
     *
     * @param int|null $code
     * @param string|null $description
     *
     * @return void
     */
    public function testAddGetPathShouldGenerateValidGetMethodDataForPathWithSuccessResponseType(?int $code, ?string $description): void
    {
        //Arrange
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForGetMethod()
            ->addResponseSchema($this->tester->getResponseSchemaDataTransfer($code, $description));
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->tester->getResponseSchemaDataTransfer(static::RESPONSE_CODE_OK);
        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        //Act
        $paths = $this->pathGenerator->getPaths();

        //Assert
        $this->assertArrayHasKey(static::TEST_PATH, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedGetPathData($code, $description), $paths[static::TEST_PATH]);
    }

    /**
     * @return void
     */
    public function testAddPostPath(): void
    {
        //Arrange
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForPostMethod();
        $requestSchemaDataTransfer = $this->tester->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->tester->getResponseSchemaDataTransfer(static::RESPONSE_CODE_CREATED);
        $this->pathGenerator->addPostPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        //Act
        $paths = $this->pathGenerator->getPaths();

        //Assert
        $this->assertArrayHasKey(static::TEST_PATH, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedPostPathData(), $paths[static::TEST_PATH]);
    }

    /**
     * @dataProvider statusCodesAndDescriptionDataProvider
     *
     * @param int|null $code
     * @param string|null $description
     *
     * @return void
     */
    public function testAddPostPathWithSuccessResponseType(?int $code, ?string $description): void
    {
        //Arrange
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForPostMethod()
            ->addResponseSchema($this->tester->getResponseSchemaDataTransfer($code, $description));
        $requestSchemaDataTransfer = $this->tester->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->tester->getResponseSchemaDataTransfer($code);
        $this->pathGenerator->addPostPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        //Act
        $paths = $this->pathGenerator->getPaths();

        //Assert
        $this->assertArrayHasKey(static::TEST_PATH, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedPostPathData($code, $description), $paths[static::TEST_PATH]);
    }

    /**
     * @return void
     */
    public function testAddPatchPath(): void
    {
        //Arrange
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForPatchMethod();
        $requestSchemaDataTransfer = $this->tester->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->tester->getResponseSchemaDataTransfer(static::RESPONSE_CODE_ACCEPTED);
        $this->pathGenerator->addPatchPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        //Act
        $paths = $this->pathGenerator->getPaths();

        //Assert
        $this->assertArrayHasKey(static::TEST_PATH_WITH_ID, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedPatchPathData(), $paths[static::TEST_PATH_WITH_ID]);
    }

    /**
     * @dataProvider statusCodesAndDescriptionDataProvider
     *
     * @param int|null $code
     * @param string|null $description
     *
     * @return void
     */
    public function testAddPatchPathWithSuccessResponseType(?int $code, ?string $description): void
    {
        //Arrange
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForPatchMethod()
            ->addResponseSchema($this->tester->getResponseSchemaDataTransfer($code, $description));
        $requestSchemaDataTransfer = $this->tester->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->tester->getResponseSchemaDataTransfer(static::RESPONSE_CODE_ACCEPTED);
        $this->pathGenerator->addPatchPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        //Act
        $paths = $this->pathGenerator->getPaths();

        //Assert
        $this->assertArrayHasKey(static::TEST_PATH_WITH_ID, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedPatchPathData($code, $description), $paths[static::TEST_PATH_WITH_ID]);
    }

    /**
     * @return void
     */
    public function testAddPatchPathWithoutPassingResponseTransferShouldGenerateValidPatchMethodDataForPath(): void
    {
        //Arrange
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForPatchMethod();
        $requestSchemaDataTransfer = $this->tester->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $this->pathGenerator->addPatchPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, null);

        //Act
        $paths = $this->pathGenerator->getPaths();

        //Assert
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
        //Arrange
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForDeleteMethod();
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $this->pathGenerator->addDeletePath($pathMethodDataTransfer, $errorSchemaDataTransfer);

        //Act
        $paths = $this->pathGenerator->getPaths();

        //Assert
        $this->assertArrayHasKey(static::TEST_PATH_WITH_ID, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedDeletePathData(), $paths[static::TEST_PATH_WITH_ID]);
    }

    /**
     * @dataProvider statusCodesAndDescriptionDataProvider
     *
     * @param int|null $code
     * @param string|null $description
     *
     * @return void
     */
    public function testAddDeletePathWithSuccessResponseType(?int $code, ?string $description): void
    {
        //Arrange
        $pathMethodDataTransfer = $this->tester->getPathMethodDataTransferForDeleteMethod()
            ->addResponseSchema($this->tester->getResponseSchemaDataTransfer($code, $description));
        $errorSchemaDataTransfer = $this->tester->getErrorSchemaDataTransfer();
        $this->pathGenerator->addDeletePath($pathMethodDataTransfer, $errorSchemaDataTransfer);

        //Act
        $paths = $this->pathGenerator->getPaths();

        //Assert
        $this->assertArrayHasKey(static::TEST_PATH_WITH_ID, $paths);
        $this->assertArraySubset($this->tester->getPathGeneratorExpectedDeletePathData($code, $description), $paths[static::TEST_PATH_WITH_ID]);
    }
}
