<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Model;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface;
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
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface
     */
    protected $resourceRelationshipsPluginAnnotationAnalyzer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaBuilderInterface $schemaBuilder
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface $resourceRelationshipsPluginAnnotationAnalyzer
     */
    public function __construct(
        ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer,
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        SchemaBuilderInterface $schemaBuilder,
        ResourceRelationshipsPluginAnnotationAnalyzerInterface $resourceRelationshipsPluginAnnotationAnalyzer
    ) {
        $this->resourceRelationshipPluginAnalyzer = $resourceRelationshipPluginAnalyzer;
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
        $this->schemaBuilder = $schemaBuilder;
        $this->resourceRelationshipsPluginAnnotationAnalyzer = $resourceRelationshipsPluginAnnotationAnalyzer;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $transferClassName
     * @param string $responseDataSchemaName
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer[]
     */
    public function getAllSchemaDataTransfersForPlugin(
        ResourceRoutePluginInterface $plugin,
        string $transferClassName,
        string $responseDataSchemaName
    ): array {
        $schemaDataTransfers = [];
        $resourceRelationships = $this->getResourceRelationshipsForResourceRoutePlugin($plugin);

        if ($resourceRelationships) {
            $schemaDataTransfers = array_merge(
                $this->createResourceRelationshipSchemaDataTransfers($responseDataSchemaName, array_keys($resourceRelationships), $transferClassName),
                $this->createIncludedSchemaDataTransfers($responseDataSchemaName, $resourceRelationships, $transferClassName)
            );
        }

        return $schemaDataTransfers;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return string[]
     */
    public function getResourceAttributesClassNamesFromPlugin(ResourceRoutePluginInterface $plugin): array
    {
        $resourceAttributesClassNames = [];
        $resourceRelationships = $this->getResourceRelationshipsForResourceRoutePlugin($plugin);

        if ($resourceRelationships) {
            foreach ($resourceRelationships as $resourceRelationship) {
                $pluginAnnotationsTransfer = $this
                    ->resourceRelationshipsPluginAnnotationAnalyzer
                    ->getResourceAttributesFromResourceRelationshipPlugin($resourceRelationship);
                $resourceAttributesClassName = $pluginAnnotationsTransfer->getResourceAttributesClassName();

                if ($resourceAttributesClassName) {
                    $resourceAttributesClassNames[] = $resourceAttributesClassName;
                }
            }
        }

        return $resourceAttributesClassNames;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface[]
     */
    protected function getResourceRelationshipsForResourceRoutePlugin(ResourceRoutePluginInterface $plugin): array
    {
        return $this->resourceRelationshipPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);
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
        $resourceRelationshipsSchemaName = $this
            ->resourceTransferAnalyzer
            ->createResourceRelationshipSchemaNameFromTransferClassName($transferClassName);

        return [
            $this->schemaBuilder->createRelationshipBaseSchema($responseDataSchemaName, $resourceRelationshipsSchemaName),
            $this->schemaBuilder->createRelationshipDataSchema($resourceRelationshipsSchemaName, $resourceRelationships),
        ];
    }
}
