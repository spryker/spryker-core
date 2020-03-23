<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor;

use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\PathAnnotationsTransfer;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRouteCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Model\PluginResourceTypeStorageInterface;
use Symfony\Component\HttpFoundation\Request;

class PluginResourceTypeStorageProcessor implements PluginResourceTypeStorageProcessorInterface
{
    protected const SCHEMA_NAME_RELATIONSHIPS_DATA = 'RestRelationshipsData';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    protected $glueAnnotationsAnalyser;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Model\PluginResourceTypeStorageInterface
     */
    protected $pluginResourceTypeStorage;

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
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    protected $resourceRouteCollection;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Model\PluginResourceTypeStorageInterface $pluginResourceTypeStorage
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzerInterface $glueAnnotationsAnalyser
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface $resourceRelationshipsPluginAnnotationAnalyzer
     */
    public function __construct(
        PluginResourceTypeStorageInterface $pluginResourceTypeStorage,
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer,
        GlueAnnotationAnalyzerInterface $glueAnnotationsAnalyser,
        ResourceRelationshipsPluginAnnotationAnalyzerInterface $resourceRelationshipsPluginAnnotationAnalyzer
    ) {
        $this->pluginResourceTypeStorage = $pluginResourceTypeStorage;
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
    public function addPluginResourceTypesToStorage(ResourceRoutePluginInterface $plugin): void
    {
        $this->resourceRouteCollection = new ResourceRouteCollection();
        $this->resourceRouteCollection = $plugin->configure($this->resourceRouteCollection);

        $this->addPluginResourceTypeToStorage(
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
    protected function addPluginResourceTypeToStorage(ResourceRoutePluginInterface $plugin, PathAnnotationsTransfer $pathAnnotationsTransfer): void
    {
        $this->addPluginResourceTypeToStorageGetResourceByIdPath($plugin, $pathAnnotationsTransfer->getGetResourceById());
        $this->addPluginResourceTypeToStorageGetResourceCollectionPath($plugin, $pathAnnotationsTransfer->getGetCollection());
        $this->addPluginResourceTypeToStoragePostResourcePath($plugin, $pathAnnotationsTransfer->getPost());
        $this->addPluginResourceTypeToStoragePatchResourcePath($plugin, $pathAnnotationsTransfer->getPatch());
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addPluginResourceTypeToStorageGetResourceByIdPath(ResourceRoutePluginInterface $plugin, ?AnnotationTransfer $annotationTransfer): void
    {
        if (!$annotationTransfer || !$this->resourceRouteCollection->has(Request::METHOD_GET)) {
            return;
        }

        $this->addResponseResourceDataSchemaNameToStorage($plugin, $annotationTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addPluginResourceTypeToStorageGetResourceCollectionPath(
        ResourceRoutePluginInterface $plugin,
        ?AnnotationTransfer $annotationTransfer
    ): void {
        if (!$annotationTransfer || !$this->resourceRouteCollection->has(Request::METHOD_GET)) {
            return;
        }

        $this->addResponseCollectionDataSchemaNameToStorage($plugin, $annotationTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addPluginResourceTypeToStoragePostResourcePath(ResourceRoutePluginInterface $plugin, ?AnnotationTransfer $annotationTransfer): void
    {
        if (!$this->resourceRouteCollection->has(Request::METHOD_POST)) {
            return;
        }

        $this->addResponseResourceDataSchemaNameToStorage($plugin, $annotationTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addPluginResourceTypeToStoragePatchResourcePath(ResourceRoutePluginInterface $plugin, ?AnnotationTransfer $annotationTransfer): void
    {
        if (!$this->resourceRouteCollection->has(Request::METHOD_PATCH)) {
            return;
        }

        $this->addResponseResourceDataSchemaNameToStorage($plugin, $annotationTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addResponseResourceDataSchemaNameToStorage(ResourceRoutePluginInterface $plugin, ?AnnotationTransfer $annotationTransfer)
    {
        $transferClassName = $this->resolveTransferClassNameForPlugin($plugin, $annotationTransfer);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName($transferClassName);
        $this->pluginResourceTypeStorage->addResourceSchemaName($plugin->getResourceType(), $responseDataSchemaName);

        if (!$plugin instanceof ResourceWithParentPluginInterface) {
            $this->addResourceRelationshipsToStorage($plugin);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addResponseCollectionDataSchemaNameToStorage(ResourceRoutePluginInterface $plugin, ?AnnotationTransfer $annotationTransfer)
    {
        $transferClassName = $this->resolveTransferClassNameForPlugin($plugin, $annotationTransfer);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionDataSchemaNameFromTransferClassName($transferClassName);
        $this->pluginResourceTypeStorage->addResourceSchemaName($plugin->getResourceType(), $responseDataSchemaName);
        $this->addResourceRelationshipsToStorage($plugin);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return string
     */
    protected function resolveTransferClassNameForPlugin(ResourceRoutePluginInterface $plugin, ?AnnotationTransfer $annotationTransfer = null): string
    {
        $transferClassName = $annotationTransfer && $annotationTransfer->getResponseAttributesClassName()
            ? $annotationTransfer->getResponseAttributesClassName()
            : $plugin->getResourceAttributesClassName();

        return $transferClassName;
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
                $responseAttributesSchema = $this
                    ->resourceTransferAnalyzer
                    ->createResponseAttributesSchemaNameFromTransferClassName(
                        $pluginAnnotationsTransfer->getResourceAttributesClassName()
                    );

                $this->pluginResourceTypeStorage->addResourceSchemaName($key, $responseAttributesSchema);
            }
        }
    }
}
