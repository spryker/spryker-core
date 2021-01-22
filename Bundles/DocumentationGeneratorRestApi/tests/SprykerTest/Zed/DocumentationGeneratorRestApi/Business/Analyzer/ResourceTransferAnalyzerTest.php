<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Codeception\Test\Unit;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\RestTestAttributesTransfer;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\TestEntityTransfer;

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
    use ArraySubsetAsserts;

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
        //Act
        $result = $this->resourceTransferAnalyzer->isTransferValid(RestTestAttributesTransfer::class);

        //Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsTransferValidShouldReturnFalseForInvalidTransferClassName(): void
    {
        //Act
        $result = $this->resourceTransferAnalyzer->isTransferValid('RestTestAttributesTransfer');

        //Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testIsTransferValidShouldReturnFalseForEntityTransferClass(): void
    {
        //Act
        $result = $this->resourceTransferAnalyzer->isTransferValid(TestEntityTransfer::class);

        //Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testGetTransferMetadataShouldReturnArrayWithCorrectTransferMetadata(): void
    {
        //Act
        $metadata = $this->resourceTransferAnalyzer->getTransferMetadata(new RestTestAttributesTransfer());

        //Assert
        $this->assertArraySubset($this->tester->getTestAttributesTransferMetadataExpectedData(), $metadata);
    }

    /**
     * @return void
     */
    public function testCreateRequestSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createRequestSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        //Assert
        $this->assertSame(static::REQUEST_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateRequestDataSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createRequestDataSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        //Assert
        $this->assertSame(static::REQUEST_DATA_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateRequestAttributesSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createRequestAttributesSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        //Assert
        $this->assertSame(static::REQUEST_ATTRIBUTES_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseCollectionSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createResponseCollectionSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        //Assert
        $this->assertSame(static::RESPONSE_COLLECTION_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseCollectionDataSchemaNameFromTransferClassNameWillGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createResponseCollectionDataSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        //Assert
        $this->assertSame(static::RESPONSE_COLLECTION_DATA_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseResourceSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createResponseResourceSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        //Assert
        $this->assertSame(static::RESPONSE_RESOURCE_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseResourceDataSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        //Assert
        $this->assertSame(static::RESPONSE_RESOURCE_DATA_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResponseAttributesSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        $this->assertSame(static::RESPONSE_ATTRIBUTES_SCHEMA_NAME, $schemaName);
    }

    /**
     * @return void
     */
    public function testCreateResourceRelationshipSchemaNameFromTransferClassNameShouldGenerateCorrectSchemaName(): void
    {
        //Act
        $schemaName = $this->resourceTransferAnalyzer->createResourceRelationshipSchemaNameFromTransferClassName(RestTestAttributesTransfer::class);

        //Assert
        $this->assertSame(static::RESOURCE_RELATIONSHIP_SCHEMA_NAME, $schemaName);
    }
}
