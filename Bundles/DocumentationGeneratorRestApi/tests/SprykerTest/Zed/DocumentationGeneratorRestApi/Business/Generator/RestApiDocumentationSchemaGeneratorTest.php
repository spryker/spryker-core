<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AnnotationTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidTransferClassException;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRoutePlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestResourceRouteWithNullableIdPlugin;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAlternativeAttributesTransfer;

/**
 * Auto-generated group annotations
 *
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
     * @var \SprykerTest\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface
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
        $this->assertArraySubset($this->tester->getSchemaGeneratorErrorSchema(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorRestLinksSchema(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorRestRelationships(), $schemas);
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
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestRequestSchema(), $schemas);
        $this->assertArraySubset($this->tester->getTestRequestDataSchema(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestRequestAttributesSchema(), $schemas);
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
        $this->assertArraySubset($this->tester->getTestResponseCollectionSchema(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseCollectionDataSchema(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseAttributesSchema(), $schemas);
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
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseResourceSchema(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseResourceDataSchema(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseAttributesSchema(), $schemas);
    }

    /**
     * @return void
     */
    public function testAddResponseResourceSchemaForPluginWithAlternativeTransferNameShouldGenerateValidResponseResourceSchemas(): void
    {
        $this->schemaGenerator->addResponseResourceSchemaForPlugin(
            new TestResourceRoutePlugin(),
            (new AnnotationTransfer())->setResponseAttributesClassName(RestTestAlternativeAttributesTransfer::class)
        );

        $schemas = $this->schemaGenerator->getSchemas();

        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_RESOURCE_DATA, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RESPONSE_ATTRIBUTES, $schemas);
        $this->assertArrayHasKey(static::SCHEMA_NAME_REST_TEST_ALTERNATIVE_RELATIONSHIPS, $schemas);
        $this->assertArraySubset($this->tester->getTestResponseResourceSchemaForAlternativeTransfer(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseResourceDataSchemaForAlternativeTransfer(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseAttributesSchemaForAlternativeTransfer(), $schemas);
        $this->assertArraySubset($this->tester->getSchemaGeneratorTestRelationshipsSchemaForAlternativeTransfer(), $schemas);
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
     * @return void
     */
    public function testAddResponseCollectionSchemaForPluginWithNullablePropertiesInTransferShouldGenerateValidResponseResourceSchemas(): void
    {
        $this->schemaGenerator->addResponseResourceSchemaForPlugin(
            new TestResourceRouteWithNullableIdPlugin()
        );

        $schemas = $this->schemaGenerator->getSchemas();

        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseAttributesSchemaForTransferWithNullableParameters(), $schemas);
    }

    /**
     * @return void
     */
    public function testAddResponseCollectionSchemaForPluginWithNullableIdInAnnotationTransferShouldGenerateValidResponseResourceSchemas(): void
    {
        $this->schemaGenerator->addResponseResourceSchemaForPlugin(
            new TestResourceRouteWithNullableIdPlugin(),
            (new AnnotationTransfer())->setIsIdNullable(true)
        );

        $schemas = $this->schemaGenerator->getSchemas();

        $this->assertArraySubset($this->tester->getSchemaGeneratorTestResponseDataSchemaWithNullableId(), $schemas);
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
}
