<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Processor;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Processor
 * @group RestApiMethodProcessorTest
 * Add your own group annotations below this line
 */
class RestApiMethodProcessorTest extends Unit
{
    protected const RESOURCE_PATH = '/test-resource';
    protected const RESOURCE_ID = '{testResourceId}';
    protected const DEFAULT_GET_RESOURCE_SUMMARY = 'Get test resource';
    protected const DEFAULT_GET_COLLECTION_SUMMARY = 'Get collection of test resource';
    protected const DEFAULT_DELETE_SUMMARY = 'Delete test resource';
    protected const DEFAULT_PATCH_SUMMARY = 'Update test resource';
    protected const DEFAULT_POST_SUMMARY = 'Create test resource';
    protected const RESOURCE_TAG = 'test-resource';
    protected const SUCCESSFUL_RESPONSE_DESCRIPTION = 'Expected response to a valid request.';
    protected const DEFAULT_RESPONSE_DESCRIPTION = 'Expected response to a bad request.';
    protected const DEFAULT_REQUEST_DESCRIPTION = 'Expected request body.';
    protected const BAD_REQUEST_RESPONSE_DESCRIPTION = 'Bad Request.';
    protected const NOT_FOUND_RESPONSE_DESCRIPTION = 'Not found.';
    protected const RESOURCE_RESPONSE_SCHEMA_REF = '#/components/schemas/RestTestResponse';
    protected const COLLECTION_RESPONSE_SCHEMA_REF = '#/components/schemas/RestTestCollectionResponse';
    protected const ERROR_SCHEMA_REF = '#/components/schemas/RestErrorMessage';
    protected const REQUEST_SCHEMA_REF = '#/components/schemas/RestTestRequest';
    protected const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';
    protected const SUMMARY = 'Test summary';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor\RestApiMethodProcessorInterface
     */
    protected $methodProcessor;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->methodProcessor = (new DocumentationGeneratorRestApiTestFactory())->createRestApiMethodProcessor();
    }

    /**
     * @return void
     */
    public function testAddGetResourcePathWithoutAnnotationsShouldAddGetResourceToPathsWithDefaultValues(): void
    {
        $this->methodProcessor->addGetResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'get' => [
                    'summary' => static::DEFAULT_GET_RESOURCE_SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'responses' => [
                        '200' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::RESOURCE_RESPONSE_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddGetResourcePathWithAnnotationsShouldAddGetResourceToPaths(): void
    {
        $annotationTransfer = (new RestApiDocumentationAnnotationTransfer())
            ->setSummary([static::SUMMARY])
            ->addHeader(static::HEADER_ACCEPT_LANGUAGE)
            ->setResponses([
                '400' => static::BAD_REQUEST_RESPONSE_DESCRIPTION,
                '404' => static::NOT_FOUND_RESPONSE_DESCRIPTION,
            ]);

        $this->methodProcessor->addGetResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            $annotationTransfer
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'get' => [
                    'summary' => static::SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'parameters' => [
                        [
                            'name' => static::HEADER_ACCEPT_LANGUAGE,
                            'in' => 'header',
                            'required' => false,
                            'schema' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::RESOURCE_RESPONSE_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                        '400' => [
                            'description' => static::BAD_REQUEST_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                        '404' => [
                            'description' => static::NOT_FOUND_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddGetResourceCollectionPathShouldAddGetCollectionToPaths(): void
    {
        $this->methodProcessor->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'get' => [
                    'summary' => static::DEFAULT_GET_COLLECTION_SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'responses' => [
                        '200' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::COLLECTION_RESPONSE_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddGetResourceCollectionPathWithAnnotationsWithEmptyResponseShouldAddGetToPathsWithEmptyResponseSchema(): void
    {
        $this->methodProcessor->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            (new RestApiDocumentationAnnotationTransfer())->setIsEmptyResponse(true)
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'get' => [
                    'summary' => static::DEFAULT_GET_COLLECTION_SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'responses' => [
                        '200' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddGetResourceCollectionPathWithAnnotationsShouldAddGetToPathsWithDataFromAnnotations(): void
    {
        $this->methodProcessor->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            (new RestApiDocumentationAnnotationTransfer())->setSummary([static::SUMMARY])
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'get' => [
                    'summary' => static::SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'responses' => [
                        '200' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddDeleteResourcePathShouldAddDeleteToPaths(): void
    {
        $this->methodProcessor->addDeleteResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'delete' => [
                    'summary' => static::DEFAULT_DELETE_SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'responses' => [
                        '204' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddPatchResourcePathShouldAddPatchToPaths(): void
    {
        $this->methodProcessor->addPatchResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            true,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'patch' => [
                    'summary' => static::DEFAULT_PATCH_SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'requestBody' => [
                        'description' => static::DEFAULT_REQUEST_DESCRIPTION,
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::REQUEST_SCHEMA_REF,
                                ],
                            ],
                        ],
                    ],
                    'security' => [
                        ['BearerAuth' => []],
                    ],
                    'responses' => [
                        '202' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::RESOURCE_RESPONSE_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddPostResourcePathShouldAddPostToPaths(): void
    {
        $this->methodProcessor->addPostResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'post' => [
                    'summary' => static::DEFAULT_POST_SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'requestBody' => [
                        'description' => static::DEFAULT_REQUEST_DESCRIPTION,
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::REQUEST_SCHEMA_REF,
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '201' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::RESOURCE_RESPONSE_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddPostResourcePathWithAnnotationsWithEmptyResponseShouldAddPostToPathsWithEmptyResponseSchema(): void
    {
        $this->methodProcessor->addPostResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            (new RestApiDocumentationAnnotationTransfer())->setIsEmptyResponse(true)
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH => [
                'post' => [
                    'summary' => static::DEFAULT_POST_SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'requestBody' => [
                        'description' => static::DEFAULT_REQUEST_DESCRIPTION,
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => static::REQUEST_SCHEMA_REF,
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '201' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }

    /**
     * @return void
     */
    public function testAddGetResourceByIdPath(): void
    {
        $this->methodProcessor->addGetResourceByIdPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            null
        );

        $generatedPaths = $this->methodProcessor->getGeneratedPaths();

        $this->assertNotEmpty($generatedPaths);
        $this->assertArrayHasKey(static::RESOURCE_PATH . '/' . static::RESOURCE_ID, $generatedPaths);
        $this->assertArraySubset([
            static::RESOURCE_PATH . '/' . static::RESOURCE_ID => [
                'get' => [
                    'summary' => static::DEFAULT_GET_RESOURCE_SUMMARY,
                    'tags' => [
                        'test-resource',
                    ],
                    'responses' => [
                        '200' => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::RESOURCE_RESPONSE_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::ERROR_SCHEMA_REF,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $generatedPaths);
    }
}
