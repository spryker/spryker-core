<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidTransferClassException;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAlternativeAttributesTransfer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAttributesTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Generator
 * @group RestApiDocumentationSchemaGeneratorTest
 * Add your own group annotations below this line
 */
class RestApiDocumentationSchemaGeneratorTest extends Unit
{
    protected const SCHEMA_REF_REST_ERROR_MESSAGE = '#/components/schemas/RestErrorMessage';
    protected const SCHEMA_REF_REST_LINKS = '#/components/schemas/RestLinks';
    protected const SCHEMA_REF_REST_RELATIONSHIPS = '#/components/schemas/RestRelationships';
    protected const SCHEMA_REF_REST_TEST_ALTERNATIVE_RELATIONSHIPS = '#/components/schemas/RestTestAlternativeRelationships';
    protected const SCHEMA_REF_REST_TEST_ALTERNATIVE_RESPONSE_ATTRIBUTES = '#/components/schemas/RestTestAlternativeAttributes';
    protected const SCHEMA_REF_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE_DATA = '#/components/schemas/RestTestAlternativeResponseData';
    protected const SCHEMA_REF_REST_TEST_RELATIONSHIPS = '#/components/schemas/RestTestRelationships';
    protected const SCHEMA_REF_REST_TEST_REQUEST_ATTRIBUTES = '#/components/schemas/RestTestRequestAttributes';
    protected const SCHEMA_REF_REST_TEST_REQUEST_DATA = '#/components/schemas/RestTestRequestData';
    protected const SCHEMA_REF_REST_TEST_RESPONSE_ATTRIBUTES = '#/components/schemas/RestTestAttributes';
    protected const SCHEMA_REF_REST_TEST_RESPONSE_COLLECTION_DATA = '#/components/schemas/RestTestCollectionResponseData';
    protected const SCHEMA_REF_REST_TEST_RESPONSE_RESOURCE_DATA = '#/components/schemas/RestTestResponseData';

    protected const SCHEMA_NAME_REST_ERROR_MESSAGE = 'RestErrorMessage';
    protected const SCHEMA_NAME_REST_LINKS = 'RestLinks';
    protected const SCHEMA_NAME_REST_RELATIONSHIPS = 'RestRelationships';
    protected const SCHEMA_NAME_REST_TEST_ALTERNATIVE_RELATIONSHIPS = 'RestTestAlternativeRelationships';
    protected const SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_ATTRIBUTES = 'RestTestAlternativeAttributes';
    protected const SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE = 'RestTestAlternativeResponse';
    protected const SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE_DATA = 'RestTestAlternativeResponseData';
    protected const SCHEMA_NAME_REST_TEST_REQUEST = 'RestTestRequest';
    protected const SCHEMA_NAME_REST_TEST_REQUEST_ATTRIBUTES = 'RestTestRequestAttributes';
    protected const SCHEMA_NAME_REST_TEST_REQUEST_DATA = 'RestTestRequestData';
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_ATTRIBUTES = 'RestTestAttributes';
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_COLLECTION = 'RestTestCollectionResponse';
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_COLLECTION_DATA = 'RestTestCollectionResponseData';
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_RESOURCE = 'RestTestResponse';
    protected const SCHEMA_NAME_REST_TEST_RESPONSE_RESOURCE_DATA = 'RestTestResponseData';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSchemaGeneratorInterface
     */
    protected $schemaGenerator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->schemaGenerator = (new DocumentationGeneratorRestApiTestFactory())->createOpenApiSpecificationSchemaGenerator();
    }

    /**
     * @return void
     */
    public function testGetRestErrorSchemaDataShouldReturnErrorSchemaReference(): void
    {
        $errorSchemaRef = $this->schemaGenerator->getRestErrorSchemaData();

        $this->assertEquals(static::SCHEMA_REF_REST_ERROR_MESSAGE, $errorSchemaRef);
    }

    /**
     * @return void
     */
    public function testGetSchemasShouldReturnDefaultSchemas(): void
    {
        $schemas = $this->schemaGenerator->getSchemas();

        $this->assertNotEmpty($schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_ERROR_MESSAGE, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_LINKS, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_RELATIONSHIPS, $schemas);
        $this->assertArraySubset($this->getErrorSchema(), $schemas);
        $this->assertArraySubset($this->getRestLinksSchema(), $schemas);
        $this->assertArraySubset($this->getRestRelationships(), $schemas);
    }

    /**
     * @return void
     */
    public function testAddRequestSchemaForPluginShouldGenerateValidRequestSchemas(): void
    {
        $this->schemaGenerator->addRequestSchemaForPlugin(new TestResourceRoutePlugin());

        $schemas = $this->schemaGenerator->getSchemas();
        $this->assertNotEmpty($schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_REQUEST, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_REQUEST_DATA, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_REQUEST_ATTRIBUTES, $schemas);
        $this->assertArraySubset($this->getTestRequestSchema(), $schemas);
        $this->assertArraySubset($this->getTestRequestDataSchema(), $schemas);
        $this->assertArraySubset($this->getTestRequestAttributesSchema(), $schemas);
    }

    /**
     * @return void
     */
    public function testAddResponseCollectionSchemaForPluginShouldGenerateValidResponseCollectionSchemas(): void
    {
        $this->schemaGenerator->addResponseCollectionSchemaForPlugin(new TestResourceRoutePlugin());

        $schemas = $this->schemaGenerator->getSchemas();

        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_RESPONSE_COLLECTION, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_RESPONSE_COLLECTION_DATA, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_RESPONSE_ATTRIBUTES, $schemas);
        $this->assertArraySubset($this->getTestResponseCollectionSchema(), $schemas);
        $this->assertArraySubset($this->getTestResponseCollectionDataSchema(), $schemas);
        $this->assertArraySubset($this->getTestResponseAttributesSchema(), $schemas);
    }

    /**
     * @return void
     */
    public function testAddResponseResourceSchemaForPluginShouldGenerateValidResponseResourceSchemas(): void
    {
        $this->schemaGenerator->addResponseResourceSchemaForPlugin(new TestResourceRoutePlugin());

        $schemas = $this->schemaGenerator->getSchemas();

        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_RESPONSE_RESOURCE, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_RESPONSE_RESOURCE_DATA, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_RESPONSE_ATTRIBUTES, $schemas);
        $this->assertArraySubset($this->getTestResponseResourceSchema(), $schemas);
        $this->assertArraySubset($this->getTestResponseResourceDataSchema(), $schemas);
        $this->assertArraySubset($this->getTestResponseAttributesSchema(), $schemas);
    }

    /**
     * @return void
     */
    public function testAddResponseResourceSchemaForPluginWithAlternativeTransferNameShouldGenerateValidResponseResourceSchemas(): void
    {
        $this->schemaGenerator->addResponseResourceSchemaForPlugin(new TestResourceRoutePlugin(), RestTestAlternativeAttributesTransfer::class);

        $schemas = $this->schemaGenerator->getSchemas();

        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE_DATA, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_ATTRIBUTES, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RELATIONSHIPS, $schemas);
        $this->assertArraySubset($this->getTestResponseResourceSchemaForAlternativeTransfer(), $schemas);
        $this->assertArraySubset($this->getTestResponseResourceDataSchemaForAlternativeTransfer(), $schemas);
        $this->assertArraySubset($this->getTestResponseAttributesSchemaForAlternativeTransfer(), $schemas);
        $this->assertArraySubset($this->getTestRelationshipsSchemaForAlternativeTransfer(), $schemas);
    }

    /**
     * @return void
     */
    public function testAddRequestSchemaForPluginShouldThrowExceptionIfPluginWithInvalidResourceAttributesClassNameIsPassed(): void
    {
        $plugin = $this->getResourceRoutePluginWithInvalidResourceAttributesClassName();

        $this->expectException(InvalidTransferClassException::class);
        $this->schemaGenerator->addRequestSchemaForPlugin($plugin);
    }

    /**
     * @return void
     */
    public function testAddResponseCollectionForPluginShouldThrowExceptionIfPluginWithInvalidResourceAttributesClassNameIsPassed(): void
    {
        $plugin = $this->getResourceRoutePluginWithInvalidResourceAttributesClassName();

        $this->expectException(InvalidTransferClassException::class);
        $this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin);
    }

    /**
     * @return void
     */
    public function testAddResponseResourceForPluginShouldThrowExceptionIfPluginWithInvalidResourceAttributesClassNameIsPassed(): void
    {
        $plugin = $this->getResourceRoutePluginWithInvalidResourceAttributesClassName();

        $this->expectException(InvalidTransferClassException::class);
        $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function getResourceRoutePluginWithInvalidResourceAttributesClassName(): MockObject
    {
        $mock = $this->getMockBuilder(TestResourceRoutePlugin::class)
            ->setMethods(['getResourceAttributesClassName'])
            ->getMock();
        $mock->method('getResourceAttributesClassName')
            ->willReturn('InvalidTransfer');

        return $mock;
    }

    /**
     * @return array
     */
    protected function getErrorSchema(): array
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
    protected function getRestLinksSchema(): array
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
    protected function getRestRelationships(): array
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
    protected function getTestRequestSchema(): array
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
    protected function getTestRequestDataSchema(): array
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
    protected function getTestRequestAttributesSchema(): array
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
    protected function getTestResponseCollectionSchema(): array
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
    protected function getTestResponseCollectionDataSchema(): array
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
    protected function getTestResponseResourceSchema(): array
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
    protected function getTestResponseResourceDataSchema(): array
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
    protected function getTestResponseAttributesSchema(): array
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
    protected function getTestResponseResourceSchemaForAlternativeTransfer(): array
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
    protected function getTestResponseResourceDataSchemaForAlternativeTransfer(): array
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
    protected function getTestResponseAttributesSchemaForAlternativeTransfer(): array
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
    protected function getTestRelationshipsSchemaForAlternativeTransfer(): array
    {
        return [
            static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RELATIONSHIPS => [
                'properties' => [
                    'test-resource-with-relationship' => [
                        'items' => [
                            '$ref' => static::SCHEMA_REF_REST_RELATIONSHIPS,
                        ],
                    ],
                ],
            ],
        ];
    }
}
