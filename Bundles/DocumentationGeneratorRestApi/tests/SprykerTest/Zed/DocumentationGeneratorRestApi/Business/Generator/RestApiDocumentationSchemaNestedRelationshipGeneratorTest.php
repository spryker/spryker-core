<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Codeception\Test\Unit;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Storage\ResourceTransferClassNameStorageInterface;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Plugin\TestNestedResourceRoutePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Generator
 * @group RestApiDocumentationSchemaNestedRelationshipGeneratorTest
 * Add your own group annotations below this line
 */
class RestApiDocumentationSchemaNestedRelationshipGeneratorTest extends Unit
{
    use ArraySubsetAsserts;

    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_FIRST_NESTED_RELATIONSHIPS = 'RestTestFirstNestedResourceRelationshipRelationships';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_FIRST_NESTED_RESPONSE_DATA = 'RestTestFirstNestedResourceRelationshipResponseData';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_REST_TEST_INCLUDED = 'RestTestIncluded';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface
     */
    protected SchemaGeneratorInterface $schemaGenerator;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Storage\ResourceTransferClassNameStorageInterface
     */
    protected ResourceTransferClassNameStorageInterface $resourceTransferClassNameStorage;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $documentationGeneratorRestApiTestFactory = new DocumentationGeneratorRestApiTestFactory();

        $resourceSchemaNameStorage = $documentationGeneratorRestApiTestFactory->createResourceSchemaNameStorage();

        $resourceSchemaNameStorage->addResourceSchemaName('test-first-nested-resource', 'RestTestFirstNestedResourceRelationshipResponseData');
        $resourceSchemaNameStorage->addResourceSchemaName('test-second-nested-resource', 'RestTestSecondNestedResourceRelationshipResponseData');
        $resourceSchemaNameStorage->addResourceSchemaName('test-third-without-annotation-nested-resource', 'RestTestThirdNestedResourceRelationshipResponseData');
        $resourceSchemaNameStorage->addResourceSchemaName('test-fourth-nested-resource', 'RestTestFourthNestedResourceRelationshipResponseData');

        $this->resourceTransferClassNameStorage = $documentationGeneratorRestApiTestFactory->createResourceTransferClassNameStorage();
        $this->schemaGenerator = $documentationGeneratorRestApiTestFactory->createOpenApiSpecificationSchemaGenerator();
    }

    /**
     * @return void
     */
    public function testAddResponseResourceSchemaForPluginShouldCreateFirstNestedResourceRelationship(): void
    {
        // Arrange
        $this->schemaGenerator->addResponseResourceSchemaForPlugin(new TestNestedResourceRoutePlugin());

        // Act
        $schemas = $this->schemaGenerator->getSchemas();

        // Assert
        $this->assertSame(
            [
                'properties' => [
                    'test-second-nested-resource' => [
                        '$ref' => '#/components/schemas/RestRelationshipsData',
                    ],
                ],
            ],
            $schemas[static::SCHEMA_NAME_REST_TEST_FIRST_NESTED_RELATIONSHIPS],
        );
    }

    /**
     * @return void
     */
    public function testAddResponseResourceSchemaForPluginShouldAddRelationshipToFirstNestedResourceRelationshipResponseData(): void
    {
        // Arrange
        $this->schemaGenerator->addResponseResourceSchemaForPlugin(new TestNestedResourceRoutePlugin());

        // Act
        $schemas = $this->schemaGenerator->getSchemas();

        // Assert
        $this->assertSame(
            ['$ref' => '#/components/schemas/RestTestFirstNestedResourceRelationshipRelationships'],
            $schemas[static::SCHEMA_NAME_REST_TEST_FIRST_NESTED_RESPONSE_DATA]['properties']['relationships'],
        );
    }

    /**
     * @return void
     */
    public function testAddResponseResourceSchemaForPluginShouldExpandRestTestIncludedWithNestedRelationshipsWithoutAnnotations(): void
    {
        // Arrange
        $this->resourceTransferClassNameStorage->addResourceTransferClassName('test-third-without-annotation-nested-resource', '');

        $this->schemaGenerator->addResponseResourceSchemaForPlugin(new TestNestedResourceRoutePlugin());

        // Act
        $schemas = $this->schemaGenerator->getSchemas();

        // Assert
        $this->assertArrayNotHasKey('RestTestThirdNestedResourceRelationshipResponseData', $schemas);
    }

    /**
     * @return void
     */
    public function testAddResponseResourceSchemaForPluginShouldExpandRestTestIncludedWithNestedRelationshipsWithDefinedRoute(): void
    {
        // Arrange
        $this->resourceTransferClassNameStorage->addResourceTransferClassName('test-third-without-annotation-nested-resource', 'RestTestThirdNestedResourceRelationshipAttributesTransfer');

        $this->schemaGenerator->addResponseResourceSchemaForPlugin(new TestNestedResourceRoutePlugin());

        // Act
        $schemas = $this->schemaGenerator->getSchemas();

        // Assert
        $this->assertSame(
            ['$ref' => '#/components/schemas/RestTestThirdNestedResourceRelationshipRelationships'],
            $schemas['RestTestThirdNestedResourceRelationshipResponseData']['properties']['relationships'],
        );
    }

    /**
     * @group her
     *
     * @return void
     */
    public function testAddResponseResourceSchemaForPluginShouldExpandRestTestIncludedWithNestedRelationships(): void
    {
        // Arrange
        $this->schemaGenerator->addResponseResourceSchemaForPlugin(new TestNestedResourceRoutePlugin());

        // Act
        $schemas = $this->schemaGenerator->getSchemas();

        // Assert
        $this->assertSame(
            [
                'oneOf' => [
                    ['$ref' => '#/components/schemas/RestTestFirstNestedResourceRelationshipResponseData'],
                    ['$ref' => '#/components/schemas/RestTestSecondNestedResourceRelationshipResponseData'],
                    ['$ref' => '#/components/schemas/RestTestThirdNestedResourceRelationshipResponseData'],
                    ['$ref' => '#/components/schemas/RestTestFourthNestedResourceRelationshipResponseData'],
                ],
            ],
            $schemas[static::SCHEMA_NAME_REST_TEST_INCLUDED]['items'],
        );
    }
}
