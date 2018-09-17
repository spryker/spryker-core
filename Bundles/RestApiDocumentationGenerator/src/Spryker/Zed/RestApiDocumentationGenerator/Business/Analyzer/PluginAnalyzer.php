<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRouteCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface;
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

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    protected $pathGenerator;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    protected $schemaGenerator;

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
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface $pathGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface $schemaGenerator
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[] $resourceRoutesPluginsProviderPlugins
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[] $resourceRelationshipCollectionPlugins
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\GlueAnnotationAnalyzerInterface $annotationsAnalyser
     */
    public function __construct(
        RestApiDocumentationPathGeneratorInterface $pathGenerator,
        RestApiDocumentationSchemaGeneratorInterface $schemaGenerator,
        array $resourceRoutesPluginsProviderPlugins,
        array $resourceRelationshipCollectionPlugins,
        GlueAnnotationAnalyzerInterface $annotationsAnalyser
    ) {
        $this->pathGenerator = $pathGenerator;
        $this->schemaGenerator = $schemaGenerator;
        $this->resourceRoutesPluginsProviderPlugins = $resourceRoutesPluginsProviderPlugins;
        $this->resourceRelationshipCollectionPlugins = $resourceRelationshipCollectionPlugins;
        $this->annotationsAnalyser = $annotationsAnalyser;
    }

    /**
     * @return void
     */
    public function createRestApiDocumentationFromPlugins(): void
    {
        foreach ($this->resourceRoutesPluginsProviderPlugins as $resourceRoutesPluginsProviderPlugin) {
            foreach ($resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins() as $plugin) {
                $parents = $this->getParentResource($plugin, $resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins());
                $resource = $plugin->getResourceType();
                $collection = $plugin->configure(new ResourceRouteCollection());
                $annotationParameters = $this->annotationsAnalyser->getParametersFromPlugin($plugin);
                $resourcePath = $this->parseParentToPath('/' . $resource, $parents);
                $errorSchema = $this->schemaGenerator->getRestErrorSchemaName();
                $responseSchema = $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin);

                if ($collection->has(Request::METHOD_GET)) {
                    $isProtected = $collection->get(Request::METHOD_GET)[static::KEY_IS_PROTECTED];
                    if ($this->isGetCollection($annotationParameters)) {
                        $collectionResponseSchema = $this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin);
                        $this->pathGenerator->addGetPath($resource, $resourcePath, $collectionResponseSchema, $errorSchema, $isProtected);
                    }
                    if ($this->isGetResource($annotationParameters)) {
                        $this->pathGenerator->addGetPath($resource, $resourcePath, $responseSchema, $errorSchema, $isProtected);
                    }
                }
                if ($collection->has(Request::METHOD_POST)) {
                    $isProtected = $collection->get(Request::METHOD_POST)[static::KEY_IS_PROTECTED];
                    $requestSchema = $this->schemaGenerator->addRequestSchemaForPlugin($plugin);
                    $this->pathGenerator->addPostPath($resource, $resourcePath, $requestSchema, $responseSchema, $errorSchema, $isProtected);
                }
                if ($collection->has(Request::METHOD_PATCH)) {
                    $isProtected = $collection->get(Request::METHOD_PATCH)[static::KEY_IS_PROTECTED];
                    $requestSchema = $this->schemaGenerator->addRequestSchemaForPlugin($plugin);
                    $this->pathGenerator->addPatchPath($resource, $resourcePath, $requestSchema, $responseSchema, $errorSchema, $isProtected);
                }
                if ($collection->has(Request::METHOD_DELETE)) {
                    $isProtected = $collection->get(Request::METHOD_DELETE)[static::KEY_IS_PROTECTED];
                    $this->pathGenerator->addDeletePath($resource, $resourcePath, $errorSchema, $isProtected);
                }
            }
        }
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
        if ($plugin instanceof ResourceWithParentPluginInterface) {
            $parent = [];
            foreach ($pluginsStack as $parentPlugin) {
                if ($plugin->getParentResourceType() === $parentPlugin->getResourceType()) {
                    $parent = [
                        static::KEY_NAME => $parentPlugin->getResourceType(),
                        static::KEY_ID => $this->getResourceIdFromResourceType($parentPlugin->getResourceType()),
                        static::KEY_PARENT => $this->getParentResource($parentPlugin, $pluginsStack),
                    ];
                    break;
                }
            }
            return $parent;
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
        $resourceTypeCamelCased = array_map(function ($key, $value) {
            if ($key === 0) {
                return $value;
            }

            return ucfirst($value);
        }, array_keys($resourceTypeExploded), $resourceTypeExploded);

        return sprintf(static::PATTERN_PATH_ID, implode('', $resourceTypeCamelCased));
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
}
