<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Codeception\Test\Unit;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAttributesTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Analyzer
 * @group ResourceTransferAnalyzerTest
 * Add your own group annotations below this line
 */
class ResourceTransferAnalyzerTest extends Unit
{
    protected const REQUEST_SCHEMA_NAME = 'RestTestRequest';
    protected const REQUEST_DATA_SCHEMA_NAME = 'RestTestRequestData';
    protected const REQUEST_ATTRIBUTES_SCHEMA_NAME = 'RestTestRequestAttributes';
    protected const RESPONSE_COLLECTION_SCHEMA_NAME = 'RestTestCollectionResponse';
    protected const RESPONSE_COLLECTION_DATA_SCHEMA_NAME = 'RestTestCollectionResponseData';
    protected const RESPONSE_RESOURCE_SCHEMA_NAME = 'RestTestResponse';
    protected const RESPONSE_RESOURCE_DATA_SCHEMA_NAME = 'RestTestResponseData';
    protected const RESPONSE_ATTRIBUTES_SCHEMA_NAME = 'RestTestAttributes';
    protected const RESOURCE_RELATIONSHIP_SCHEMA_NAME = 'RestTestRelationships';

    /**
     * @var \SprykerTest\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected $resourceTransferAnalyzer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->resourceTransferAnalyzer = new ResourceTransferAnalyzer();
    }

    /**
     * @return void
     */
    public function testIsTransferValidShouldReturnTrueForValidTransferClassName(): void
    {
        $result = $this->resourceTransferAnalyzer->isTransferValid(RestTestAttributesTransfer::class);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsTransferValidShouldReturnFalseForInvalidTransferClassName(): void
    {
        $result = $this->resourceTransferAnalyzer->isTransferValid('RestTestAttributesTransfer');

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testGetTransferMetadataShouldReturnArrayWithCorrectTransferMetada(): void
    {
        $metadata = $this->resourceTransferAnalyzer->getTransferMetadata(new RestTestAttributesTransfer());

        $this->assertArraySubset($this->tester->getTestAttributesTransferMetadataExpectedData(), $metadata);
    }

    /**
     * @return void
     */
    public function testCreateRequestSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createRequestSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::REQUEST_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateRequestDataSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createRequestDataSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::REQUEST_DATA_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateRequestAttributesSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createRequestAttributesSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::REQUEST_ATTRIBUTES_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseCollectionSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createResponseCollectionSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::RESPONSE_COLLECTION_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseCollectionDataSchemaNameFromTransferClassNameWillGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createResponseCollectionDataSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::RESPONSE_COLLECTION_DATA_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseResourceSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createResponseResourceSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::RESPONSE_RESOURCE_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseResourceDataSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::RESPONSE_RESOURCE_DATA_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseAttributesSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::RESPONSE_ATTRIBUTES_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResourceRelationshipSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        $schemaName = $this->resourceTransferAnalyzer->createResourceRelationshipSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertEquals(static::RESOURCE_RELATIONSHIP_SCHEMA_NAME, $schemaName);
    }
}
