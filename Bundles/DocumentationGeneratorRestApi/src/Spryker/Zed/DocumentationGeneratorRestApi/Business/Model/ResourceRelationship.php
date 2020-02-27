<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Model;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaBuilderInterface;

class ResourceRelationship implements ResourceRelationshipInterface
{
    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface
     */
    protected $resourceRelationshipPluginAnalyzer;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected $resourceTransferAnalyzer;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaBuilderInterface
     */
    protected $schemaBuilder;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaBuilderInterface $schemaBuilder
     */
    public function __construct(
        ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer,
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        SchemaBuilderInterface $schemaBuilder
    ) {
        $this->resourceRelationshipPluginAnalyzer = $resourceRelationshipPluginAnalyzer;
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
        $this->schemaBuilder = $schemaBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $transferClassName
     * @param string $responseDataSchemaName
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer[]
     */
    public function getSchemaDataTransfersFromForPlugin(ResourceRoutePluginInterface $plugin, string $transferClassName, string $responseDataSchemaName): array
    {
        $schemaDataTransfers = [];
        $resourceRelationships = $this->resourceRelationshipPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);

        if ($resourceRelationships) {
            $schemaDataTransfers = array_merge(
                $this->createResourceRelationshipSchemaDataTransfers($responseDataSchemaName, $resourceRelationships, $transferClassName),
                $this->createIncludedSchemaDataTransfers($responseDataSchemaName, $resourceRelationships, $transferClassName)
            );
        }

        return $schemaDataTransfers;
    }

    /**
     * @param string $responseDataSchemaName
     * @param array $resourceRelationships
     * @param string $transferClassName
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer[]
     */
    protected function createIncludedSchemaDataTransfers(
        string $responseDataSchemaName,
        array $resourceRelationships,
        string $transferClassName
    ): array {
        $includedSchemaName = $this->resourceTransferAnalyzer->createIncludedSchemaNameFromTransferClassName($transferClassName);

        return [
            $this->schemaBuilder->createIncludedBaseSchema($responseDataSchemaName, $includedSchemaName),
            $this->schemaBuilder->createIncludedDataSchema($includedSchemaName, $resourceRelationships),
        ];
    }

    /**
     * @param string $responseDataSchemaName
     * @param array $resourceRelationships
     * @param string $transferClassName
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer[]
     */
    protected function createResourceRelationshipSchemaDataTransfers(
        string $responseDataSchemaName,
        array $resourceRelationships,
        string $transferClassName
    ): array {
        $resourceRelationshipsSchemaName = $this->resourceTransferAnalyzer->createResourceRelationshipSchemaNameFromTransferClassName($transferClassName);

        return [
            $this->schemaBuilder->createRelationshipBaseSchema($responseDataSchemaName, $resourceRelationshipsSchemaName),
            $this->schemaBuilder->createRelationshipDataSchema($resourceRelationshipsSchemaName, $resourceRelationships),
        ];
    }
}
