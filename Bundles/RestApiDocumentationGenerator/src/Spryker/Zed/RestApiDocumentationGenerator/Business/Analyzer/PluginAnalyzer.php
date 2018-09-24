<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig;
use Symfony\Component\HttpFoundation\Request;

class PluginAnalyzer implements PluginAnalyzerInterface
{
    protected const KEY_IS_PROTECTED = 'is_protected';
    protected const KEY_NAME = 'name';
    protected const KEY_ID = 'id';
    protected const KEY_PARENT = 'parent';
    protected const KEY_PATHS = 'paths';
    protected const KEY_SCHEMAS = 'schemas';

    protected const PATTERN_PATH_WITH_PARENT = '/%s/%s%s';
    protected const PATTERN_PATH_ID = '{%sId}';

    protected const PATTERN_SUMMARY_GET_RESOURCE = 'Get %s';
    protected const PATTERN_SUMMARY_GET_COLLECTION = 'Get collection of %s';
    protected const PATTERN_SUMMARY_POST_RESOURCE = 'Add %s';
    protected const PATTERN_SUMMARY_PATCH_RESOURCE = 'Update %s';
    protected const PATTERN_SUMMARY_DELETE_RESOURCE = 'Delete %s';

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    protected $pathGenerator;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    protected $schemaGenerator;

    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    protected $resourceRouteCollection;

    /**
     * @var \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    protected $resourceRoutesPluginsProviderPlugins;

    /**
     * @var \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    protected $resourceRelationshipCollectionPlugins;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface
     */
    protected $annotationsAnalyser;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface
     */
    protected $textInflector;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface $pathGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface $schemaGenerator
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[] $resourceRoutesPluginsProviderPlugins
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[] $resourceRelationshipCollectionPlugins
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface $annotationsAnalyser
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface $textInflector
     */
    public function __construct(
        RestApiDocumentationPathGeneratorInterface $pathGenerator,
        RestApiDocumentationSchemaGeneratorInterface $schemaGenerator,
        ResourceRouteCollectionInterface $resourceRouteCollection,
        array $resourceRoutesPluginsProviderPlugins,
        array $resourceRelationshipCollectionPlugins,
        GlueAnnotationAnalyzerInterface $annotationsAnalyser,
        RestApiDocumentationGeneratorToTextInflectorInterface $textInflector
    ) {
        $this->pathGenerator = $pathGenerator;
        $this->schemaGenerator = $schemaGenerator;
        $this->resourceRouteCollection = $resourceRouteCollection;
        $this->resourceRoutesPluginsProviderPlugins = $resourceRoutesPluginsProviderPlugins;
        $this->resourceRelationshipCollectionPlugins = $resourceRelationshipCollectionPlugins;
        $this->annotationsAnalyser = $annotationsAnalyser;
        $this->textInflector = $textInflector;
    }

    /**
     * @return void
     */
    public function createRestApiDocumentationFromPlugins(): void
    {
        foreach ($this->resourceRoutesPluginsProviderPlugins as $resourceRoutesPluginsProviderPlugin) {
            foreach ($resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins() as $plugin) {
                $annotationParameters = $this->annotationsAnalyser->getParametersFromPlugin($plugin);
                $this->handleGetResourcePath($plugin, $resourceRoutesPluginsProviderPlugin, $annotationParameters);
                $this->handlePostResourcePath($plugin, $resourceRoutesPluginsProviderPlugin, $annotationParameters);
                $this->handlePatchResourcePath($plugin, $resourceRoutesPluginsProviderPlugin, $annotationParameters);
                $this->handleDeleteResourcePath($plugin, $resourceRoutesPluginsProviderPlugin, $annotationParameters);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface $resourceRoutesPluginsProviderPlugin
     * @param array $annotationParameters
     *
     * @return void
     */
    protected function handleGetResourcePath(ResourceRoutePluginInterface $plugin, ResourceRoutePluginsProviderPluginInterface $resourceRoutesPluginsProviderPlugin, array $annotationParameters): void
    {
        $collection = $plugin->configure($this->resourceRouteCollection);
        if (!$collection->has(Request::METHOD_GET)) {
            return;
        }
        $resourcePath = $this->parseParentToPath(
            '/' . $plugin->getResourceType(),
            $this->getParentResource($plugin, $resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins())
        );

        $pathDataTransfer = new RestApiDocumentationPathMethodDataTransfer();
        $pathDataTransfer->setResource($plugin->getResourceType());
        $pathDataTransfer->setPath($resourcePath);

        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $responseSchema = $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin);

        $pathDataTransfer->setIsProtected($collection->get(Request::METHOD_GET)[static::KEY_IS_PROTECTED]);
        $pathDataTransfer->setSummary(
            $this->getSummary($annotationParameters, Request::METHOD_GET, static::PATTERN_SUMMARY_GET_COLLECTION, $pathDataTransfer->getResource())
        );
        $pathDataTransfer->setHeaders($this->getMethodHeadersFromAnnotations($annotationParameters, Request::METHOD_GET));
        $this->addResponsesToPathData($pathDataTransfer, $errorSchema, $this->getMethodResponsesFromAnnotations($annotationParameters, Request::METHOD_GET));

        if ($this->isGetCollection($annotationParameters)) {
            $pathDataTransfer->setSummary($this->getSummary($annotationParameters, Request::METHOD_GET, static::PATTERN_SUMMARY_GET_COLLECTION, $pathDataTransfer->getResource()));
            $collectionResponseSchema = $this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin);
            $this->pathGenerator->addGetPath($pathDataTransfer, $collectionResponseSchema, $errorSchema);
        }
        if ($this->isGetResource($annotationParameters)) {
            $pathDataTransfer->setPath($resourcePath . '/' . $this->getResourceIdFromResourceType($pathDataTransfer->getResource()));
            $pathDataTransfer->setSummary($this->getSummary($annotationParameters, Request::METHOD_GET, static::PATTERN_SUMMARY_GET_RESOURCE, $pathDataTransfer->getResource()));
            $this->pathGenerator->addGetPath($pathDataTransfer, $responseSchema, $errorSchema);
        }
        if (!$this->isGetResource($annotationParameters) && !$this->isGetCollection($annotationParameters)) {
            $pathDataTransfer->setSummary($this->getSummary($annotationParameters, Request::METHOD_GET, static::PATTERN_SUMMARY_GET_RESOURCE, $pathDataTransfer->getResource()));
            $this->pathGenerator->addGetPath($pathDataTransfer, $responseSchema, $errorSchema);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface $resourceRoutesPluginsProviderPlugin
     * @param array $annotationParameters
     *
     * @return void
     */
    protected function handlePostResourcePath(ResourceRoutePluginInterface $plugin, ResourceRoutePluginsProviderPluginInterface $resourceRoutesPluginsProviderPlugin, array $annotationParameters): void
    {
        $collection = $plugin->configure($this->resourceRouteCollection);
        if (!$collection->has(Request::METHOD_POST)) {
            return;
        }

        $resourcePath = $this->parseParentToPath(
            '/' . $plugin->getResourceType(),
            $this->getParentResource($plugin, $resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins())
        );

        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $responseSchema = $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin);
        $requestSchema = $this->schemaGenerator->addRequestSchemaForPlugin($plugin);

        $pathDataTransfer = new RestApiDocumentationPathMethodDataTransfer();
        $pathDataTransfer->setResource($plugin->getResourceType());
        $pathDataTransfer->setPath($resourcePath);
        $pathDataTransfer->setIsProtected($collection->get(Request::METHOD_POST)[static::KEY_IS_PROTECTED]);
        $pathDataTransfer->setSummary(
            $this->getSummary($annotationParameters, Request::METHOD_POST, static::PATTERN_SUMMARY_POST_RESOURCE, $pathDataTransfer->getResource())
        );
        $pathDataTransfer->setHeaders($this->getMethodHeadersFromAnnotations($annotationParameters, Request::METHOD_POST));
        $this->addResponsesToPathData($pathDataTransfer, $errorSchema, $this->getMethodResponsesFromAnnotations($annotationParameters, Request::METHOD_POST));

        $this->pathGenerator->addPostPath($pathDataTransfer, $requestSchema, $responseSchema, $errorSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface $resourceRoutesPluginsProviderPlugin
     * @param array $annotationParameters
     *
     * @return void
     */
    protected function handlePatchResourcePath(ResourceRoutePluginInterface $plugin, ResourceRoutePluginsProviderPluginInterface $resourceRoutesPluginsProviderPlugin, array $annotationParameters): void
    {
        $collection = $plugin->configure($this->resourceRouteCollection);
        if (!$collection->has(Request::METHOD_PATCH)) {
            return;
        }

        $resourcePath = $this->parseParentToPath(
            '/' . $plugin->getResourceType(),
            $this->getParentResource($plugin, $resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins())
        );

        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $responseSchema = $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin);
        $requestSchema = $this->schemaGenerator->addRequestSchemaForPlugin($plugin);

        $pathDataTransfer = new RestApiDocumentationPathMethodDataTransfer();
        $pathDataTransfer->setResource($plugin->getResourceType());
        $pathDataTransfer->setPath($resourcePath . '/' . $this->getResourceIdFromResourceType($pathDataTransfer->getResource()));
        $pathDataTransfer->setIsProtected($collection->get(Request::METHOD_PATCH)[static::KEY_IS_PROTECTED]);
        $pathDataTransfer->setSummary(
            $this->getSummary($annotationParameters, Request::METHOD_PATCH, static::PATTERN_SUMMARY_PATCH_RESOURCE, $pathDataTransfer->getResource())
        );
        $pathDataTransfer->setHeaders($this->getMethodHeadersFromAnnotations($annotationParameters, Request::METHOD_PATCH));
        $this->addResponsesToPathData($pathDataTransfer, $errorSchema, $this->getMethodResponsesFromAnnotations($annotationParameters, Request::METHOD_PATCH));

        $this->pathGenerator->addPatchPath($pathDataTransfer, $requestSchema, $responseSchema, $errorSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface $resourceRoutesPluginsProviderPlugin
     * @param array $annotationParameters
     *
     * @return void
     */
    protected function handleDeleteResourcePath(ResourceRoutePluginInterface $plugin, ResourceRoutePluginsProviderPluginInterface $resourceRoutesPluginsProviderPlugin, array $annotationParameters): void
    {
        $collection = $plugin->configure($this->resourceRouteCollection);
        if (!$collection->has(Request::METHOD_DELETE)) {
            return;
        }

        $resourcePath = $this->parseParentToPath(
            '/' . $plugin->getResourceType(),
            $this->getParentResource($plugin, $resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins())
        );

        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();

        $pathDataTransfer = new RestApiDocumentationPathMethodDataTransfer();
        $pathDataTransfer->setResource($plugin->getResourceType());
        $pathDataTransfer->setPath($resourcePath . '/' . $this->getResourceIdFromResourceType($pathDataTransfer->getResource()));
        $pathDataTransfer->setIsProtected($collection->get(Request::METHOD_DELETE)[static::KEY_IS_PROTECTED]);
        $pathDataTransfer->setSummary(
            $this->getSummary($annotationParameters, Request::METHOD_DELETE, static::PATTERN_SUMMARY_DELETE_RESOURCE, $pathDataTransfer->getResource())
        );
        $pathDataTransfer->setHeaders($this->getMethodHeadersFromAnnotations($annotationParameters, Request::METHOD_DELETE));
        $this->addResponsesToPathData($pathDataTransfer, $errorSchema, $this->getMethodResponsesFromAnnotations($annotationParameters, Request::METHOD_DELETE));

        $this->pathGenerator->addDeletePath($pathDataTransfer, $errorSchema);
    }

    /**
     * @return array
     */
    public function getRestApiDocumentationData(): array
    {
        return [
            static::KEY_PATHS => $this->pathGenerator->getPaths(),
            static::KEY_SCHEMAS => $this->schemaGenerator->getSchemas(),
        ];
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param array $pluginsStack
     *
     * @return array|null
     */
    protected function getParentResource(ResourceRoutePluginInterface $plugin, array $pluginsStack): ?array
    {
        if (!$plugin instanceof ResourceWithParentPluginInterface) {
            return null;
        }

        foreach ($pluginsStack as $parentPlugin) {
            if ($plugin->getParentResourceType() === $parentPlugin->getResourceType()) {
                return [
                    static::KEY_NAME => $parentPlugin->getResourceType(),
                    static::KEY_ID => $this->getResourceIdFromResourceType($parentPlugin->getResourceType()),
                    static::KEY_PARENT => $this->getParentResource($parentPlugin, $pluginsStack),
                ];
            }
        }

        return null;
    }

    /**
     * @param string $resourceType
     *
     * @return string
     */
    protected function getResourceIdFromResourceType(string $resourceType): string
    {
        $resourceTypeExploded = explode('-', $resourceType);
        $resourceTypeCamelCased = array_map(function ($value) {
            return ucfirst($this->textInflector->singularize($value));
        }, $resourceTypeExploded);

        return sprintf(static::PATTERN_PATH_ID, lcfirst(implode('', $resourceTypeCamelCased)));
    }

    /**
     * @param string $path
     * @param array|null $parent
     *
     * @return string
     */
    protected function parseParentToPath(string $path, ?array $parent): string
    {
        if (!$parent) {
            return $path;
        }

        return $this->parseParentToPath(
            sprintf(static::PATTERN_PATH_WITH_PARENT, $parent[static::KEY_NAME], $parent[static::KEY_ID], $path),
            $parent[static::KEY_PARENT]
        );
    }

    /**
     * @param array $annotationParameters
     *
     * @return bool
     */
    protected function isGetCollection(array $annotationParameters): bool
    {
        return $annotationParameters
            && isset($annotationParameters[Request::METHOD_GET][RestApiDocumentationGeneratorConfig::ANNOTATION_KEY_GET_COLLECTION])
            && $annotationParameters[Request::METHOD_GET][RestApiDocumentationGeneratorConfig::ANNOTATION_KEY_GET_COLLECTION];
    }

    /**
     * @param array $annotationParameters
     *
     * @return bool
     */
    protected function isGetResource(array $annotationParameters): bool
    {
        return $annotationParameters
            && isset($annotationParameters[Request::METHOD_GET][RestApiDocumentationGeneratorConfig::ANNOTATION_KEY_GET_RESOURCE])
            && $annotationParameters[Request::METHOD_GET][RestApiDocumentationGeneratorConfig::ANNOTATION_KEY_GET_RESOURCE];
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     * @param array $responses
     *
     * @return void
     */
    protected function addResponsesToPathData(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer,
        array $responses
    ): void {
        foreach ($responses as $code => $description) {
            $responseSchemaDataTransfer = clone $errorSchemaDataTransfer;
            $responseSchemaDataTransfer->setCode($code);
            $responseSchemaDataTransfer->setDescription($description);

            $pathMethodDataTransfer->addResponseSchema($responseSchemaDataTransfer);
        }
    }

    /**
     * @param array $annotationParameters
     * @param string $method
     *
     * @return array
     */
    protected function getMethodHeadersFromAnnotations(array $annotationParameters, string $method): array
    {
        return $annotationParameters[$method]['headers'] ?? [];
    }

    /**
     * @param array $annotationParameters
     * @param string $method
     *
     * @return array
     */
    protected function getMethodResponsesFromAnnotations(array $annotationParameters, string $method): array
    {
        return $annotationParameters[$method]['responses'] ?? [];
    }

    /**
     * @param array $annotationParameters
     * @param string $method
     * @param string $defaultSummaryPattern
     * @param string $resource
     *
     * @return string
     */
    protected function getSummary(array $annotationParameters, string $method, string $defaultSummaryPattern, string $resource): string
    {
        return $annotationParameters[$method]['summary']
            ?? sprintf($defaultSummaryPattern, str_replace('-', ' ', $resource));
    }
}
