<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Processor;

use Generated\Shared\Transfer\SchemaDataTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Builder\SchemaBuilderInterface;

class ResourceRelationshipProcessor implements ResourceRelationshipProcessorInterface
{
    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected $resourceTransferAnalyzer;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Builder\SchemaBuilderInterface
     */
    protected $schemaBuilder;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Builder\SchemaBuilderInterface $schemaBuilder
     */
    public function __construct(
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        SchemaBuilderInterface $schemaBuilder
    ) {
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
        $this->schemaBuilder = $schemaBuilder;
    }

    /**
     * @param array<string> $relationships
     * @param string $transferClassName
     * @param string $responseDataSchemaName
     *
     * @return array<\Generated\Shared\Transfer\SchemaDataTransfer>
     */
    public function getRelationshipSchemaDataTransfers(
        array $relationships,
        string $transferClassName,
        string $responseDataSchemaName
    ): array {
        if ($relationships === []) {
            return [];
        }

        $resourceRelationshipsSchemaName = $this
            ->resourceTransferAnalyzer
            ->createResourceRelationshipSchemaNameFromTransferClassName($transferClassName);

        return [
            $this->schemaBuilder->createRelationshipBaseSchema($responseDataSchemaName, $resourceRelationshipsSchemaName),
            $this->schemaBuilder->createRelationshipDataSchema($resourceRelationshipsSchemaName, $relationships),
        ];
    }

    /**
     * @param string $transferClassName
     * @param array<string> $relationshipResponses
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function getIncludeDataSchema(
        string $transferClassName,
        array $relationshipResponses
    ): SchemaDataTransfer {
        $includedSchemaName = $this->resourceTransferAnalyzer->createIncludedSchemaNameFromTransferClassName($transferClassName);

        return $this->schemaBuilder->createIncludedDataSchema($includedSchemaName, $relationshipResponses);
    }

    /**
     * @param string $transferClassName
     * @param string $responseSchemaName
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function getIncludeBaseSchema(
        string $transferClassName,
        string $responseSchemaName
    ): SchemaDataTransfer {
        $includedSchemaName = $this->resourceTransferAnalyzer->createIncludedSchemaNameFromTransferClassName($transferClassName);

        return $this->schemaBuilder->createIncludedBaseSchema($responseSchemaName, $includedSchemaName);
    }
}
