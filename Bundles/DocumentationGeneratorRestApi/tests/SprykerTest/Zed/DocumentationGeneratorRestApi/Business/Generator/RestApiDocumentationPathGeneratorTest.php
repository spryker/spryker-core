<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;

/**
 * Auto-generated group annotations
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
    protected const METHOD_GET = 'get';
    protected const METHOD_POST = 'post';
    protected const METHOD_PATCH = 'patch';
    protected const METHOD_DELETE = 'delete';
    protected const SUMMARY = 'Test summary.';
    protected const PATH = '/test-path';
    protected const PATH_WITH_ID = '/test-path/{test-resource-id}';
    protected const PARAMETER_IN_HEADER = 'header';
    protected const PARAMETER_IN_PATH = 'path';
    protected const RESOURCE_ID = 'test-resource-id';
    protected const RESOURCE = 'test-resource';
    protected const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';
    protected const RESPONSE_CODE_BAD_REQUEST = 400;
    protected const RESPONSE_CODE_NOT_FOUND = 404;
    protected const RESPONSE_CODE_DEFAULT = 'default';
    protected const RESPONSE_CODE_OK = 200;
    protected const RESPONSE_CODE_CREATED = 201;
    protected const RESPONSE_CODE_ACCEPTED = 202;
    protected const RESPONSE_CODE_NO_CONTENT = 204;
    protected const RESPONSE_DESCRIPTION_BAD_REQUEST = 'Bad Request';
    protected const RESPONSE_DESCRIPTION_NOT_FOUND = 'Item not found';
    protected const RESPONSE_DESCRIPTION_DEFAULT = 'Expected response to a bad request.';
    protected const RESPONSE_DESCRIPTION_SUCCESS = 'Expected response to a valid request.';
    protected const REQUEST_DESCRIPTION = 'Expected request body.';
    protected const SCHEMA_REF_REST_REQUEST = '#/components/schemas/RestTestRequest';
    protected const SCHEMA_REF_REST_ERROR_MESSAGE = '#/components/schemas/RestErrorMessage';
    protected const SCHEMA_REF_REST_RESPONSE = '#/components/schemas/RestTestResponse';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationPathGeneratorInterface
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
        $pathMethodDataTransfer = $this->getPathMethodDataTransferForGetMethod();
        $errorSchemaDataTransfer = $this->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->getResponseSchemaDataTransfer(static::RESPONSE_CODE_OK);
        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::PATH, $paths);
        $this->assertArraySubset($this->getExpectedGetPathData(), $paths[static::PATH]);
    }

    /**
     * @return void
     */
    public function testAddPostPath(): void
    {
        $pathMethodDataTransfer = $this->getPathMethodDataTransferForPostMethod();
        $requestSchemaDataTransfer = $this->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->getResponseSchemaDataTransfer(static::RESPONSE_CODE_CREATED);
        $this->pathGenerator->addPostPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::PATH, $paths);
        $this->assertArraySubset($this->getExpectedPostPathData(), $paths[static::PATH]);
    }

    /**
     * @return void
     */
    public function testAddPatchPath(): void
    {
        $pathMethodDataTransfer = $this->getPathMethodDataTransferForPatchMethod();
        $requestSchemaDataTransfer = $this->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->getErrorSchemaDataTransfer();
        $responseSchemaDataTransfer = $this->getResponseSchemaDataTransfer(static::RESPONSE_CODE_ACCEPTED);
        $this->pathGenerator->addPatchPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, $responseSchemaDataTransfer);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::PATH_WITH_ID, $paths);
        $this->assertArraySubset($this->getExpectedPatchPathData(), $paths[static::PATH_WITH_ID]);
    }

    /**
     * @return void
     */
    public function testAddPatchPathWithoutPassingResponseTransferShouldGenerateValidPatchMethodDataForPath(): void
    {
        $pathMethodDataTransfer = $this->getPathMethodDataTransferForPatchMethod();
        $requestSchemaDataTransfer = $this->getRequestSchemaDataTransfer();
        $errorSchemaDataTransfer = $this->getErrorSchemaDataTransfer();
        $this->pathGenerator->addPatchPath($pathMethodDataTransfer, $requestSchemaDataTransfer, $errorSchemaDataTransfer, null);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::PATH_WITH_ID, $paths);
        $this->assertArrayHasKey(static::METHOD_PATCH, $paths[static::PATH_WITH_ID]);
        $this->assertArrayHasKey('responses', $paths[static::PATH_WITH_ID][static::METHOD_PATCH]);
        $this->assertArrayNotHasKey('content', $paths[static::PATH_WITH_ID][static::METHOD_PATCH]['responses']);
    }

    /**
     * @return void
     */
    public function testAddDeletePath(): void
    {
        $pathMethodDataTransfer = $this->getPathMethodDataTransferForDeleteMethod();
        $errorSchemaDataTransfer = $this->getErrorSchemaDataTransfer();
        $this->pathGenerator->addDeletePath($pathMethodDataTransfer, $errorSchemaDataTransfer);

        $paths = $this->pathGenerator->getPaths();

        $this->assertArrayHasKey(static::PATH_WITH_ID, $paths);
        $this->assertArraySubset($this->getExpectedDeletePathData(), $paths[static::PATH_WITH_ID]);
    }

    /**
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer
     */
    protected function getPathMethodDataTransferForGetMethod(): OpenApiSpecificationPathMethodDataTransfer
    {
        return (new OpenApiSpecificationPathMethodDataTransfer())
            ->setSummary([static::SUMMARY])
            ->setResource(static::RESOURCE)
            ->setPath(static::PATH)
            ->setMethod(static::METHOD_GET)
            ->setIsProtected(false)
            ->addHeader(static::HEADER_ACCEPT_LANGUAGE)
            ->addResponseSchema($this->getNotFoundResponseSchema())
            ->addResponseSchema($this->getBadRequestResponseSchema());
    }

    /**
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer
     */
    protected function getPathMethodDataTransferForPostMethod(): OpenApiSpecificationPathMethodDataTransfer
    {
        return (new OpenApiSpecificationPathMethodDataTransfer())
            ->setSummary([static::SUMMARY])
            ->setResource(static::RESOURCE)
            ->setPath(static::PATH)
            ->setMethod(static::METHOD_POST)
            ->setIsProtected(false)
            ->addHeader(static::HEADER_ACCEPT_LANGUAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer
     */
    protected function getPathMethodDataTransferForPatchMethod(): OpenApiSpecificationPathMethodDataTransfer
    {
        return (new OpenApiSpecificationPathMethodDataTransfer())
            ->setSummary([static::SUMMARY])
            ->setResource(static::RESOURCE)
            ->setPath(static::PATH_WITH_ID)
            ->setMethod(static::METHOD_PATCH)
            ->setIsProtected(false)
            ->addHeader(static::HEADER_ACCEPT_LANGUAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer
     */
    protected function getPathMethodDataTransferForDeleteMethod(): OpenApiSpecificationPathMethodDataTransfer
    {
        return (new OpenApiSpecificationPathMethodDataTransfer())
            ->setSummary([static::SUMMARY])
            ->setResource(static::RESOURCE)
            ->setPath(static::PATH_WITH_ID)
            ->setMethod(static::METHOD_PATCH)
            ->setIsProtected(false);
    }

    /**
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer
     */
    protected function getRequestSchemaDataTransfer(): OpenApiSpecificationPathSchemaDataTransfer
    {
        return (new OpenApiSpecificationPathSchemaDataTransfer())
            ->setSchemaReference(static::SCHEMA_REF_REST_REQUEST);
    }

    /**
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer
     */
    protected function getErrorSchemaDataTransfer(): OpenApiSpecificationPathSchemaDataTransfer
    {
        return (new OpenApiSpecificationPathSchemaDataTransfer())
            ->setCode(static::RESPONSE_CODE_DEFAULT)
            ->setDescription(static::RESPONSE_DESCRIPTION_DEFAULT)
            ->setSchemaReference(static::SCHEMA_REF_REST_ERROR_MESSAGE);
    }

    /**
     * @param int $code
     *
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer
     */
    protected function getResponseSchemaDataTransfer(int $code): OpenApiSpecificationPathSchemaDataTransfer
    {
        return (new OpenApiSpecificationPathSchemaDataTransfer())
            ->setCode($code)
            ->setDescription(static::RESPONSE_DESCRIPTION_SUCCESS)
            ->setSchemaReference(static::SCHEMA_REF_REST_RESPONSE);
    }

    /**
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer
     */
    protected function getNotFoundResponseSchema(): OpenApiSpecificationPathSchemaDataTransfer
    {
        return (new OpenApiSpecificationPathSchemaDataTransfer())
            ->setCode(static::RESPONSE_CODE_NOT_FOUND)
            ->setDescription(static::RESPONSE_DESCRIPTION_NOT_FOUND)
            ->setSchemaReference(static::SCHEMA_REF_REST_ERROR_MESSAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer
     */
    protected function getBadRequestResponseSchema(): OpenApiSpecificationPathSchemaDataTransfer
    {
        return (new OpenApiSpecificationPathSchemaDataTransfer())
            ->setCode(static::RESPONSE_CODE_BAD_REQUEST)
            ->setDescription(static::RESPONSE_DESCRIPTION_BAD_REQUEST)
            ->setSchemaReference(static::SCHEMA_REF_REST_ERROR_MESSAGE);
    }

    /**
     * @return array
     */
    protected function getExpectedGetPathData(): array
    {
        return [
            static::METHOD_GET => [
                'summary' => static::SUMMARY,
                'tags' => [
                    static::RESOURCE,
                ],
                'parameters' => [
                    [
                        'name' => static::HEADER_ACCEPT_LANGUAGE,
                        'in' => static::PARAMETER_IN_HEADER,
                        'required' => false,
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'responses' => [
                    static::RESPONSE_CODE_OK => [
                        'description' => static::RESPONSE_DESCRIPTION_SUCCESS,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_RESPONSE,
                                ],
                            ],
                        ],
                    ],
                    static::RESPONSE_CODE_BAD_REQUEST => [
                        'description' => static::RESPONSE_DESCRIPTION_BAD_REQUEST,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
                                ],
                            ],
                        ],
                    ],
                    static::RESPONSE_CODE_NOT_FOUND => [
                        'description' => static::RESPONSE_DESCRIPTION_NOT_FOUND,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
                                ],
                            ],
                        ],
                    ],
                    static::RESPONSE_CODE_DEFAULT => [
                        'description' => static::RESPONSE_DESCRIPTION_DEFAULT,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExpectedPostPathData(): array
    {
        return [
            static::METHOD_POST => [
                'summary' => static::SUMMARY,
                'tags' => [
                    static::RESOURCE,
                ],
                'parameters' => [
                    [
                        'name' => static::HEADER_ACCEPT_LANGUAGE,
                        'in' => static::PARAMETER_IN_HEADER,
                        'required' => false,
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'responses' => [
                    static::RESPONSE_CODE_CREATED => [
                        'description' => static::RESPONSE_DESCRIPTION_SUCCESS,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_RESPONSE,
                                ],
                            ],
                        ],
                    ],
                    static::RESPONSE_CODE_DEFAULT => [
                        'description' => static::RESPONSE_DESCRIPTION_DEFAULT,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExpectedPatchPathData(): array
    {
        return [
            static::METHOD_PATCH => [
                'summary' => static::SUMMARY,
                'tags' => [
                    static::RESOURCE,
                ],
                'parameters' => [
                    [
                        'name' => static::RESOURCE_ID,
                        'in' => static::PARAMETER_IN_PATH,
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                    [
                        'name' => static::HEADER_ACCEPT_LANGUAGE,
                        'in' => static::PARAMETER_IN_HEADER,
                        'required' => false,
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'responses' => [
                    static::RESPONSE_CODE_ACCEPTED => [
                        'description' => static::RESPONSE_DESCRIPTION_SUCCESS,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_RESPONSE,
                                ],
                            ],
                        ],
                    ],
                    static::RESPONSE_CODE_DEFAULT => [
                        'description' => static::RESPONSE_DESCRIPTION_DEFAULT,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExpectedDeletePathData(): array
    {
        return [
            static::METHOD_DELETE => [
                'summary' => static::SUMMARY,
                'tags' => [
                    static::RESOURCE,
                ],
                'parameters' => [
                    [
                        'name' => static::RESOURCE_ID,
                        'in' => static::PARAMETER_IN_PATH,
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'responses' => [
                    static::RESPONSE_CODE_NO_CONTENT => [
                        'description' => static::RESPONSE_DESCRIPTION_SUCCESS,
                    ],
                    static::RESPONSE_CODE_DEFAULT => [
                        'description' => static::RESPONSE_DESCRIPTION_DEFAULT,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
