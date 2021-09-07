<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi;

use Codeception\Actor;
use Generated\Shared\Transfer\PathMethodDataTransfer;
use Generated\Shared\Transfer\PathParameterComponentTransfer;
use Generated\Shared\Transfer\PathSchemaDataTransfer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAlternativeAttributesTransfer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAttributesTransfer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAttributesWithNullablePropertyTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class DocumentationGeneratorRestApiTester extends Actor
{
    use _generated\DocumentationGeneratorRestApiTesterActions;

    /**
     * @var string
     */
    protected const METHOD_GET = 'get';
    /**
     * @var string
     */
    protected const METHOD_POST = 'post';
    /**
     * @var string
     */
    protected const METHOD_PATCH = 'patch';
    /**
     * @var string
     */
    protected const METHOD_DELETE = 'delete';
    /**
     * @var string
     */
    protected const TEST_SUMMARY = 'Test summary.';
    /**
     * @var string
     */
    protected const TEST_PATH = '/test-path';
    /**
     * @var string
     */
    protected const TEST_PATH_WITH_ID = '/test-path/{test-resource-id}';
    /**
     * @var string
     */
    protected const PARAMETER_IN_HEADER = 'header';
    /**
     * @var string
     */
    protected const PARAMETER_IN_PATH = 'path';
    /**
     * @var string
     */
    protected const TEST_RESOURCE_ID = 'test-resource-id';
    /**
     * @var string
     */
    protected const TEST_RESOURCE = 'test-resource';
    /**
     * @var string
     */
    protected const TEST_RESOURCE_PATH = '/test-resource';
    /**
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';
    /**
     * @var int
     */
    protected const RESPONSE_CODE_BAD_REQUEST = 400;
    /**
     * @var int
     */
    protected const RESPONSE_CODE_NOT_FOUND = 404;
    /**
     * @var string
     */
    protected const RESPONSE_CODE_DEFAULT = 'default';
    /**
     * @var int
     */
    protected const RESPONSE_CODE_OK = 200;
    /**
     * @var int
     */
    protected const RESPONSE_CODE_CREATED = 201;
    /**
     * @var int
     */
    protected const RESPONSE_CODE_NO_CONTENT = 204;
    /**
     * @var string
     */
    protected const RESPONSE_DESCRIPTION_BAD_REQUEST = 'Bad Request';
    /**
     * @var string
     */
    protected const RESPONSE_DESCRIPTION_NOT_FOUND = 'Item not found';
    /**
     * @var string
     */
    protected const RESPONSE_DESCRIPTION_DEFAULT = 'Expected response to a bad request.';
    /**
     * @var string
     */
    protected const RESPONSE_DESCRIPTION_SUCCESS = 'Expected response to a valid request.';

    /**
     * @var string
     */
    protected const TEST_RESOURCE_ID_WITH_BRACKETS = '{testResourceId}';
    /**
     * @var string
     */
    protected const DEFAULT_GET_RESOURCE_SUMMARY = 'Get test resource.';
    /**
     * @var string
     */
    protected const DEFAULT_GET_COLLECTION_SUMMARY = 'Get collection of test resource.';
    /**
     * @var string
     */
    protected const DEFAULT_DELETE_SUMMARY = 'Delete test resource.';
    /**
     * @var string
     */
    protected const DEFAULT_PATCH_SUMMARY = 'Update test resource.';
    /**
     * @var string
     */
    protected const DEFAULT_POST_SUMMARY = 'Create test resource.';
    /**
     * @var string
     */
    protected const SUCCESSFUL_RESPONSE_DESCRIPTION = 'Expected response to a valid request.';
    /**
     * @var string
     */
    protected const DEFAULT_RESPONSE_DESCRIPTION = 'Expected response to a bad request.';
    /**
     * @var string
     */
    protected const DEFAULT_REQUEST_DESCRIPTION = 'Expected request body.';
    /**
     * @var string
     */
    protected const BAD_REQUEST_RESPONSE_DESCRIPTION = 'Bad Request.';
    /**
     * @var string
     */
    protected const NOT_FOUND_RESPONSE_DESCRIPTION = 'Not found.';

    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_RESPONSE_COLLECTION = '#/components/schemas/RestTestCollectionResponse';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_REQUEST = '#/components/schemas/RestTestRequest';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_ERROR_MESSAGE = '#/components/schemas/RestErrorMessage';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_RESPONSE = '#/components/schemas/RestTestResponse';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_LINKS = '#/components/schemas/RestLinks';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_RELATIONSHIPS_DATA = '#/components/schemas/RestRelationshipsData';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_ALTERNATIVE_RELATIONSHIPS = '#/components/schemas/RestTestAlternativeRelationships';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_ALTERNATIVE_RESPONSE_ATTRIBUTES = '#/components/schemas/RestTestAlternativeAttributes';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE_DATA = '#/components/schemas/RestTestAlternativeResponseData';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_RELATIONSHIPS = '#/components/schemas/RestTestRelationships';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_REQUEST_ATTRIBUTES = '#/components/schemas/RestTestRequestAttributes';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_REQUEST_DATA = '#/components/schemas/RestTestRequestData';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_RESPONSE_ATTRIBUTES = '#/components/schemas/RestTestAttributes';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_RESPONSE_COLLECTION_DATA = '#/components/schemas/RestTestCollectionResponseData';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_RESPONSE_RESOURCE_DATA = '#/components/schemas/RestTestResponseData';
    /**
     * @var string
     */
    protected const SCHEMA_REF_REST_TEST_ATTRIBUTES_WITH_NULLABLE_PROPERTY = '#/components/schemas/RestTestAttributesWithNullableProperty';
    /**
     * @var string
     */
    protected const SHEMA_REF_REST_TEST_ATTRIBUTES_WITH_NULLABLE_PROPERTY_TRANSFER = '#/components/schemas/RestTestAttributesWithNullablePropertyTransfer';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_ERROR_MESSAGE = 'RestErrorMessage';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_LINKS = 'RestLinks';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_RELATIONSHIPS = 'RestRelationships';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_ALTERNATIVE_RELATIONSHIPS = 'RestTestAlternativeRelationships';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_ATTRIBUTES = 'RestTestAlternativeAttributes';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE = 'RestTestAlternativeResponse';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE_DATA = 'RestTestAlternativeResponseData';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_REQUEST = 'RestTestRequest';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_REQUEST_ATTRIBUTES = 'RestTestRequestAttributes';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_REQUEST_DATA = 'RestTestRequestData';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_ATTRIBUTES = 'RestTestAttributes';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_COLLECTION = 'RestTestCollectionResponse';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_COLLECTION_DATA = 'RestTestCollectionResponseData';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_RESOURCE = 'RestTestResponse';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_RESOURCE_DATA = 'RestTestResponseData';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_ATTRIBUTES_WITH_NULLABLE_PROPERTY = 'RestTestAttributesWithNullableProperty';
    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_ATTRIBUTES_WITH_NULLABLE_PROPERTY_TRANSFER = 'RestTestAttributesWithNullablePropertyTransfer';

    /**
     * @return array
     */
    public function getTestAttributesTransferMetadataExpectedData(): array
    {
        return [
            'attribute1' => [
                'type' => 'string',
                'name_underscore' => 'attribute1',
                'is_collection' => false,
                'is_transfer' => false,
                'rest_request_parameter' => 'no',
            ],
            'attribute2' => [
                'type' => 'string',
                'name_underscore' => 'attribute2',
                'is_collection' => false,
                'is_transfer' => false,
                'rest_request_parameter' => 'required',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getRestApiDocumentationFromPluginsExpectedResult(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../Business/Fixtures/glue_annotation_analyzer_expected_result.json'), true);
    }

    /**
     * @return \Generated\Shared\Transfer\PathMethodDataTransfer
     */
    public function getPathMethodDataTransferForGetMethod(): PathMethodDataTransfer
    {
        return (new PathMethodDataTransfer())
            ->setSummary([static::TEST_SUMMARY])
            ->setResource(static::TEST_RESOURCE)
            ->setPath(static::TEST_PATH)
            ->setMethod(static::METHOD_GET)
            ->setIsProtected(false)
            ->addParameter($this->getAcceptLanguageHeaderPathParameterComponent())
            ->addResponseSchema($this->getNotFoundResponseSchema())
            ->addResponseSchema($this->getBadRequestResponseSchema());
    }

    /**
     * @return \Generated\Shared\Transfer\PathMethodDataTransfer
     */
    public function getPathMethodDataTransferForPostMethod(): PathMethodDataTransfer
    {
        return (new PathMethodDataTransfer())
            ->setSummary([static::TEST_SUMMARY])
            ->setResource(static::TEST_RESOURCE)
            ->setPath(static::TEST_PATH)
            ->setMethod(static::METHOD_POST)
            ->setIsProtected(false)
            ->addParameter($this->getAcceptLanguageHeaderPathParameterComponent());
    }

    /**
     * @return \Generated\Shared\Transfer\PathMethodDataTransfer
     */
    public function getPathMethodDataTransferForPatchMethod(): PathMethodDataTransfer
    {
        return (new PathMethodDataTransfer())
            ->setSummary([static::TEST_SUMMARY])
            ->setResource(static::TEST_RESOURCE)
            ->setPath(static::TEST_PATH_WITH_ID)
            ->setMethod(static::METHOD_PATCH)
            ->setIsProtected(false)
            ->addParameter($this->getAcceptLanguageHeaderPathParameterComponent());
    }

    /**
     * @return \Generated\Shared\Transfer\PathMethodDataTransfer
     */
    public function getPathMethodDataTransferForDeleteMethod(): PathMethodDataTransfer
    {
        return (new PathMethodDataTransfer())
            ->setSummary([static::TEST_SUMMARY])
            ->setResource(static::TEST_RESOURCE)
            ->setPath(static::TEST_PATH_WITH_ID)
            ->setMethod(static::METHOD_PATCH)
            ->setIsProtected(false);
    }

    /**
     * @return \Generated\Shared\Transfer\PathSchemaDataTransfer
     */
    public function getRequestSchemaDataTransfer(): PathSchemaDataTransfer
    {
        return (new PathSchemaDataTransfer())
            ->setSchemaReference(static::SCHEMA_REF_REST_REQUEST);
    }

    /**
     * @return \Generated\Shared\Transfer\PathSchemaDataTransfer
     */
    public function getErrorSchemaDataTransfer(): PathSchemaDataTransfer
    {
        return (new PathSchemaDataTransfer())
            ->setCode(static::RESPONSE_CODE_DEFAULT)
            ->setDescription(static::RESPONSE_DESCRIPTION_DEFAULT)
            ->setSchemaReference(static::SCHEMA_REF_REST_ERROR_MESSAGE);
    }

    /**
     * @param int $code
     * @param string|null $description
     *
     * @return \Generated\Shared\Transfer\PathSchemaDataTransfer
     */
    public function getResponseSchemaDataTransfer(int $code, ?string $description = null): PathSchemaDataTransfer
    {
        return (new PathSchemaDataTransfer())
            ->setCode($code)
            ->setDescription($description ?? static::RESPONSE_DESCRIPTION_SUCCESS)
            ->setSchemaReference(static::SCHEMA_REF_REST_RESPONSE);
    }

    /**
     * @return \Generated\Shared\Transfer\PathSchemaDataTransfer
     */
    public function getNotFoundResponseSchema(): PathSchemaDataTransfer
    {
        return (new PathSchemaDataTransfer())
            ->setCode(static::RESPONSE_CODE_NOT_FOUND)
            ->setDescription(static::RESPONSE_DESCRIPTION_NOT_FOUND)
            ->setSchemaReference(static::SCHEMA_REF_REST_ERROR_MESSAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\PathSchemaDataTransfer
     */
    public function getBadRequestResponseSchema(): PathSchemaDataTransfer
    {
        return (new PathSchemaDataTransfer())
            ->setCode(static::RESPONSE_CODE_BAD_REQUEST)
            ->setDescription(static::RESPONSE_DESCRIPTION_BAD_REQUEST)
            ->setSchemaReference(static::SCHEMA_REF_REST_ERROR_MESSAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\PathParameterComponentTransfer
     */
    public function getAcceptLanguageHeaderPathParameterComponent(): PathParameterComponentTransfer
    {
        return (new PathParameterComponentTransfer())
            ->setName(static::HEADER_ACCEPT_LANGUAGE)
            ->setIn(static::PARAMETER_IN_HEADER);
    }

    /**
     * @param int|null $code
     * @param string|null $description
     *
     * @return array
     */
    public function getPathGeneratorExpectedGetPathData(?int $code = null, ?string $description = null): array
    {
        return [
            static::METHOD_GET => [
                'summary' => static::TEST_SUMMARY,
                'tags' => [
                    static::TEST_RESOURCE,
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
                    $code ?? static::RESPONSE_CODE_OK => [
                        'description' => $description ?? static::RESPONSE_DESCRIPTION_SUCCESS,
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
     * @param int|null $code
     * @param string|null $description
     *
     * @return array
     */
    public function getPathGeneratorExpectedPostPathData(?int $code = null, ?string $description = null): array
    {
        return [
            static::METHOD_POST => [
                'summary' => static::TEST_SUMMARY,
                'tags' => [
                    static::TEST_RESOURCE,
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
                    $code ?? static::RESPONSE_CODE_CREATED => [
                        'description' => $description ?? static::RESPONSE_DESCRIPTION_SUCCESS,
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
     * @param int|null $code
     * @param string|null $description
     *
     * @return array
     */
    public function getPathGeneratorExpectedPatchPathData(?int $code = null, ?string $description = null): array
    {
        return [
            static::METHOD_PATCH => [
                'summary' => static::TEST_SUMMARY,
                'tags' => [
                    static::TEST_RESOURCE,
                ],
                'parameters' => [
                    [
                        'name' => static::TEST_RESOURCE_ID,
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
                    $code ?? static::RESPONSE_CODE_OK => [
                        'description' => $description ?? static::RESPONSE_DESCRIPTION_SUCCESS,
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
     * @param int|null $code
     * @param string|null $description
     *
     * @return array
     */
    public function getPathGeneratorExpectedDeletePathData(?int $code = null, ?string $description = null): array
    {
        return [
            static::METHOD_DELETE => [
                'summary' => static::TEST_SUMMARY,
                'tags' => [
                    static::TEST_RESOURCE,
                ],
                'parameters' => [
                    [
                        'name' => static::TEST_RESOURCE_ID,
                        'in' => static::PARAMETER_IN_PATH,
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'responses' => [
                    $code ?? static::RESPONSE_CODE_NO_CONTENT => [
                        'description' => $description ?? static::RESPONSE_DESCRIPTION_SUCCESS,
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
    public function getSchemaGeneratorErrorSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_ERROR_MESSAGE => [
                'properties' => [
                    'status' => [
                        'type' => 'integer',
                    ],
                    'code' => [
                        'type' => 'string',
                    ],
                    'detail' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorRestLinksSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_LINKS => [
                'properties' => [
                    'self' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorRestRelationships(): array
    {
        return [
            static::SCHEMA_NAME_REST_RELATIONSHIPS => [
                'properties' => [
                    'id' => [
                        'type' => 'string',
                    ],
                    'type' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestRequestSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_REQUEST => [
                'properties' => [
                    'data' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_REQUEST_DATA,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTestRequestDataSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_REQUEST_DATA => [
                'properties' => [
                    'type' => [
                        'type' => 'string',
                    ],
                    'attributes' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_REQUEST_ATTRIBUTES,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestRequestAttributesSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_REQUEST_ATTRIBUTES => [
                'properties' => [
                    RestTestAttributesTransfer::ATTRIBUTE2 => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    RestTestAttributesTransfer::ATTRIBUTE2,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTestResponseCollectionSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_RESPONSE_COLLECTION => [
                'properties' => [
                    'data' => [
                        'items' => [
                            '$ref' => static::SCHEMA_REF_REST_TEST_RESPONSE_COLLECTION_DATA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestResponseCollectionDataSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_RESPONSE_COLLECTION_DATA => [
                'properties' => [
                    'type' => [
                        'type' => 'string',
                    ],
                    'id' => [
                        'type' => 'string',
                    ],
                    'attributes' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_RESPONSE_ATTRIBUTES,
                    ],
                    'links' => [
                        '$ref' => static::SCHEMA_REF_REST_LINKS,
                    ],
                    'relationships' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_RELATIONSHIPS,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestResponseResourceSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_RESPONSE_RESOURCE => [
                'properties' => [
                    'data' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_RESPONSE_RESOURCE_DATA,
                    ],
                    'links' => [
                        '$ref' => static::SCHEMA_REF_REST_LINKS,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestResponseResourceDataSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_RESPONSE_RESOURCE_DATA => [
                'properties' => [
                    'type' => [
                        'type' => 'string',
                    ],
                    'id' => [
                        'type' => 'string',
                    ],
                    'attributes' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_RESPONSE_ATTRIBUTES,
                    ],
                    'links' => [
                        '$ref' => static::SCHEMA_REF_REST_LINKS,
                    ],
                    'relationships' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_RELATIONSHIPS,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestResponseAttributesSchema(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_RESPONSE_ATTRIBUTES => [
                'properties' => [
                    RestTestAttributesTransfer::ATTRIBUTE1 => [
                        'type' => 'string',
                    ],
                    RestTestAttributesTransfer::ATTRIBUTE2 => [
                        'type' => 'string',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTestResponseResourceSchemaForAlternativeTransfer(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE => [
                'properties' => [
                    'data' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE_DATA,
                    ],
                    'links' => [
                        '$ref' => static::SCHEMA_REF_REST_LINKS,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestResponseResourceDataSchemaForAlternativeTransfer(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE_DATA => [
                'properties' => [
                    'type' => [
                        'type' => 'string',
                    ],
                    'id' => [
                        'type' => 'string',
                    ],
                    'attributes' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_ALTERNATIVE_RESPONSE_ATTRIBUTES,
                    ],
                    'links' => [
                        '$ref' => static::SCHEMA_REF_REST_LINKS,
                    ],
                    'relationships' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_ALTERNATIVE_RELATIONSHIPS,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestResponseAttributesSchemaForAlternativeTransfer(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_ATTRIBUTES => [
                'properties' => [
                    RestTestAlternativeAttributesTransfer::ATTRIBUTE3 => [
                        'type' => 'string',
                    ],
                    RestTestAlternativeAttributesTransfer::ATTRIBUTE4 => [
                        'type' => 'string',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestRelationshipsSchemaForAlternativeTransfer(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RELATIONSHIPS => [
                'properties' => [
                    'test-resource-with-relationship' => [
                        '$ref' => static::SCHEMA_REF_REST_RELATIONSHIPS_DATA,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestResponseAttributesSchemaForTransferWithNullableParameters(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_ATTRIBUTES_WITH_NULLABLE_PROPERTY => [
                'properties' => [
                    RestTestAttributesWithNullablePropertyTransfer::ATTRIBUTE1 => [
                        'type' => 'string',
                        'nullable' => true,
                    ],
                    RestTestAttributesWithNullablePropertyTransfer::ATTRIBUTE2 => [
                        'type' => 'string',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSchemaGeneratorTestResponseDataSchemaWithNullableId(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_ATTRIBUTES_WITH_NULLABLE_PROPERTY_TRANSFER => [
                'properties' => [
                    'data' => [
                        '$ref' => static::SHEMA_REF_REST_TEST_ATTRIBUTES_WITH_NULLABLE_PROPERTY_TRANSFER,
                    ],
                    'links' => [
                        '$ref' => static::SCHEMA_REF_REST_LINKS,
                    ],
                    'type' => [
                        'type' => 'string',
                    ],
                    'id' => [
                        'type' => 'string',
                        'nullable' => true,
                    ],
                    'attributes' => [
                        '$ref' => static::SCHEMA_REF_REST_TEST_ATTRIBUTES_WITH_NULLABLE_PROPERTY,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getMethodProcessorGetResourcePathWithoutAnnotationsExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
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
                                        '$ref' => static::SCHEMA_REF_REST_RESPONSE,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
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
            ],
        ];
    }

    /**
     * @return array
     */
    public function getMethodProcessorGetResourcePathWithAnnotationsExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
                'get' => [
                    'summary' => static::TEST_SUMMARY,
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
                                        '$ref' => static::SCHEMA_REF_REST_RESPONSE,
                                    ],
                                ],
                            ],
                        ],
                        '400' => [
                            'description' => static::BAD_REQUEST_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
                                    ],
                                ],
                            ],
                        ],
                        '404' => [
                            'description' => static::NOT_FOUND_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
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
            ],
        ];
    }

    /**
     * @return array
     */
    public function getMethodProcessorGetResourceCollectionWithoutAnnotationsExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
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
                                        '$ref' => static::SCHEMA_REF_REST_TEST_RESPONSE_COLLECTION,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
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
            ],
        ];
    }

    /**
     * @return array
     */
    public function getMethodProcessorGetResourceCollectionPathWithAnnotationsWithEmptyResponseExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
                'get' => [
                    'summary' => static::DEFAULT_GET_COLLECTION_SUMMARY,
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
                                        '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
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
     * @return array
     */
    public function getMethodProcessorGetResourceCollectionPathWithAnnotationsExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
                'get' => [
                    'summary' => static::TEST_SUMMARY,
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
                                        '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
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
     * @return array
     */
    public function getMethodProcessorDeleteResourcePathExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
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
                                        '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
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
     * @return array
     */
    public function getMethodProcessorPatchResourcePathExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
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
                                    '$ref' => static::SCHEMA_REF_REST_REQUEST,
                                ],
                            ],
                        ],
                    ],
                    'security' => [
                        ['BearerAuth' => []],
                    ],
                    'responses' => [
                        static::RESPONSE_CODE_OK => [
                            'description' => static::SUCCESSFUL_RESPONSE_DESCRIPTION,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => static::SCHEMA_REF_REST_RESPONSE,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
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
            ],
        ];
    }

    /**
     * @return array
     */
    public function getMethodProcessorPostResourcePathExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
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
                                    '$ref' => static::SCHEMA_REF_REST_REQUEST,
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
                                        '$ref' => static::SCHEMA_REF_REST_RESPONSE,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
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
            ],
        ];
    }

    /**
     * @return array
     */
    public function getMethodProcessorPostResourcePathWithAnnotationsWithEmptyResponseExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH => [
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
                                    '$ref' => static::SCHEMA_REF_REST_REQUEST,
                                ],
                            ],
                        ],
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
                                        '$ref' => static::SCHEMA_REF_REST_ERROR_MESSAGE,
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
     * @return array
     */
    public function getMethodProcessorGetResourceByIdPathExpectedData(): array
    {
        return [
            static::TEST_RESOURCE_PATH . '/' . static::TEST_RESOURCE_ID_WITH_BRACKETS => [
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
                                        '$ref' => static::SCHEMA_REF_REST_RESPONSE,
                                    ],
                                ],
                            ],
                        ],
                        'default' => [
                            'description' => static::DEFAULT_RESPONSE_DESCRIPTION,
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
            ],
        ];
    }
}
