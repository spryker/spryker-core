<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor;

use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\PathAnnotationsTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Storage\ResourceSchemaNameStorageInterface;

class ResourceSchemaNameStorageProcessor implements ResourceSchemaNameStorageProcessorInterface
{
    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    protected $glueAnnotationsAnalyser;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Storage\ResourceSchemaNameStorageInterface
     */
    protected $resourceSchemaNameStorage;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected $resourceTransferAnalyzer;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface
     */
    protected $resourceRelationshipPluginAnalyzer;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface
     */
    protected $resourceRelationshipsPluginAnnotationAnalyzer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Storage\ResourceSchemaNameStorageInterface $resourceSchemaNameStorage
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface $glueAnnotationsAnalyser
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface $resourceRelationshipsPluginAnnotationAnalyzer
     */
    public function __construct(
        ResourceSchemaNameStorageInterface $resourceSchemaNameStorage,
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer,
        GlueAnnotationAnalyzerInterface $glueAnnotationsAnalyser,
        ResourceRelationshipsPluginAnnotationAnalyzerInterface $resourceRelationshipsPluginAnnotationAnalyzer
    ) {
        $this->resourceSchemaNameStorage = $resourceSchemaNameStorage;
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
        $this->resourceRelationshipPluginAnalyzer = $resourceRelationshipPluginAnalyzer;
        $this->glueAnnotationsAnalyser = $glueAnnotationsAnalyser;
        $this->resourceRelationshipsPluginAnnotationAnalyzer = $resourceRelationshipsPluginAnnotationAnalyzer;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return void
     */
    public function addResourceSchemaNamesToStorage(ResourceRoutePluginInterface $plugin): void
    {
        $this->addResourceSchemaNameToStorage(
            $plugin,
            $this->glueAnnotationsAnalyser->getResourceParametersFromPlugin($plugin)
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\PathAnnotationsTransfer $pathAnnotationsTransfer
     *
     * @return void
     */
    protected function addResourceSchemaNameToStorage(ResourceRoutePluginInterface $plugin, PathAnnotationsTransfer $pathAnnotationsTransfer): void
    {
        if ($pathAnnotationsTransfer->getGetResourceById()) {
            $this->addResponseResourceDataSchemaNameToStorage($plugin, $pathAnnotationsTransfer->getGetResourceById());
        }
        if ($pathAnnotationsTransfer->getGetCollection()) {
            $this->addResponseCollectionDataSchemaNameToStorage($plugin, $pathAnnotationsTransfer->getGetCollection());
        }

        $this->addResourceRelationshipsToStorage($plugin);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     *
     * @return void
     */
    protected function addResponseResourceDataSchemaNameToStorage(ResourceRoutePluginInterface $plugin, AnnotationTransfer $annotationTransfer)
    {
        $transferClassName = $this->resolveTransferClassNameForPlugin($plugin, $annotationTransfer);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName($transferClassName);
        $this->resourceSchemaNameStorage->addResourceSchemaName($plugin->getResourceType(), $responseDataSchemaName);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     *
     * @return void
     */
    protected function addResponseCollectionDataSchemaNameToStorage(ResourceRoutePluginInterface $plugin, AnnotationTransfer $annotationTransfer)
    {
        $transferClassName = $this->resolveTransferClassNameForPlugin($plugin, $annotationTransfer);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionDataSchemaNameFromTransferClassName($transferClassName);
        $this->resourceSchemaNameStorage->addResourceSchemaName($plugin->getResourceType(), $responseDataSchemaName);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     *
     * @return string
     */
    protected function resolveTransferClassNameForPlugin(ResourceRoutePluginInterface $plugin, AnnotationTransfer $annotationTransfer): string
    {
        return $annotationTransfer->getResponseAttributesClassName() ?: $plugin->getResourceAttributesClassName();
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return void
     */
    protected function addResourceRelationshipsToStorage(ResourceRoutePluginInterface $plugin): void
    {
        $resourceRelationships = $this->resourceRelationshipPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);
        if (!$resourceRelationships) {
            return;
        }

        foreach ($resourceRelationships as $key => $resourceRelationship) {
            $pluginAnnotationsTransfer = $this
                ->resourceRelationshipsPluginAnnotationAnalyzer
                ->getResourceAttributesFromResourceRelationshipPlugin($resourceRelationship);

            if ($pluginAnnotationsTransfer->getResourceAttributesClassName()) {
                $responseResourceDataSchema = $this
                    ->resourceTransferAnalyzer
                    ->createResponseResourceDataSchemaNameFromTransferClassName(
                        $pluginAnnotationsTransfer->getResourceAttributesClassName()
                    );

                $this->resourceSchemaNameStorage->addResourceSchemaName($key, $responseResourceDataSchema);
            }
        }
    }
}
