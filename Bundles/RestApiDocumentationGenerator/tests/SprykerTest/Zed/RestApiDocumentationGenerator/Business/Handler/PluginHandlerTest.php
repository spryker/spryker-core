<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Handler;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorTestFactory;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRoutePlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group RestApiDocumentationGenerator
 * @group Business
 * @group Handler
 * @group PluginHandlerTest
 * Add your own group annotations below this line
 */
class PluginHandlerTest extends Unit
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
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Handler\PluginHandlerInterface
     */
    protected $pluginHandler;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->pluginHandler = (new RestApiDocumentationGeneratorTestFactory())->createPluginHandler();
    }

    /**
     * @return void
     */
    public function testAddGetResourcePathWithoutAnnotationsShouldAddGetResourceToPathsWithDefaultValues(): void
    {
        $this->pluginHandler->addGetResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            null
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
            ->setSummary(static::SUMMARY)
            ->addHeader(static::HEADER_ACCEPT_LANGUAGE)
            ->setResponses([
                '400' => static::BAD_REQUEST_RESPONSE_DESCRIPTION,
                '404' => static::NOT_FOUND_RESPONSE_DESCRIPTION,
            ]);

        $this->pluginHandler->addGetResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            $annotationTransfer
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
        $this->pluginHandler->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            null
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
        $this->pluginHandler->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            (new RestApiDocumentationAnnotationTransfer())->setIsEmptyResponse(true)
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
        $this->pluginHandler->addGetResourceCollectionPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            (new RestApiDocumentationAnnotationTransfer())->setSummary(static::SUMMARY)
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
        $this->pluginHandler->addDeleteResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            null
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
        $this->pluginHandler->addPatchResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            true,
            null
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
        $this->pluginHandler->addPostResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            null
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
        $this->pluginHandler->addPostResourcePath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            (new RestApiDocumentationAnnotationTransfer())->setIsEmptyResponse(true)
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
        $this->pluginHandler->addGetResourceByIdPath(
            new TestResourceRoutePlugin(),
            static::RESOURCE_PATH,
            false,
            static::RESOURCE_ID,
            null
        );

        $generatedPaths = $this->pluginHandler->getGeneratedPaths();

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
