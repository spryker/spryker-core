<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Handler;

use Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer;
use Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface;

class PluginHandler implements PluginHandlerInterface
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
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface $pathGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface $schemaGenerator
     */
    public function __construct(
        RestApiDocumentationPathGeneratorInterface $pathGenerator,
        RestApiDocumentationSchemaGeneratorInterface $schemaGenerator
    ) {
        $this->pathGenerator = $pathGenerator;
        $this->schemaGenerator = $schemaGenerator;
    }

    /**
     * @return array
     */
    public function getGeneratedPaths(): array
    {
        return $this->pathGenerator->getPaths();
    }

    /**
     * @return array
     */
    public function getGeneratedSchemas(): array
    {
        return $this->schemaGenerator->getSchemas();
    }

    /**
     * @return array
     */
    public function getGeneratedSecuritySchemes(): array
    {
        return $this->schemaGenerator->getSecuritySchemes();
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param string $idResource
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addGetResourcePath(ResourceRoutePluginInterface $plugin, string $resourcePath, bool $isProtected, string $idResource, ?RestApiDocumentationAnnotationTransfer $annotationTransfer): void
    {
        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        if (!$annotationTransfer || (!$annotationTransfer->getGetResource() && !$annotationTransfer->getGetCollection())) {
            $this->addGetResourcePathWithoutId($plugin, $pathDataTransfer);

            return;
        }

        if ($annotationTransfer->getGetCollection()) {
            $this->addGetCollectionPath($plugin, $pathDataTransfer);
        }

        if ($annotationTransfer->getGetResource()) {
            $this->addGetResourceWithId($plugin, $pathDataTransfer, $idResource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addPostResourcePath(ResourceRoutePluginInterface $plugin, string $resourcePath, bool $isProtected, ?RestApiDocumentationAnnotationTransfer $annotationTransfer): void
    {
        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $responseSchema = $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin);
        $requestSchema = $this->schemaGenerator->addRequestSchemaForPlugin($plugin);

        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        if (!$pathDataTransfer->getSummary()) {
            $pathDataTransfer->setSummary($this->getDefaultMethodSummary(static::PATTERN_SUMMARY_POST_RESOURCE, $plugin->getResourceType()));
        }

        $this->pathGenerator->addPostPath($pathDataTransfer, $requestSchema, $responseSchema, $errorSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addPatchResourcePath(ResourceRoutePluginInterface $plugin, string $resourcePath, bool $isProtected, ?RestApiDocumentationAnnotationTransfer $annotationTransfer): void
    {
        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $responseSchema = $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin);
        $requestSchema = $this->schemaGenerator->addRequestSchemaForPlugin($plugin);

        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        if (!$pathDataTransfer->getSummary()) {
            $pathDataTransfer->setSummary($this->getDefaultMethodSummary(static::PATTERN_SUMMARY_PATCH_RESOURCE, $plugin->getResourceType()));
        }

        $this->pathGenerator->addPatchPath($pathDataTransfer, $requestSchema, $responseSchema, $errorSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addDeleteResourcePath(ResourceRoutePluginInterface $plugin, string $resourcePath, bool $isProtected, ?RestApiDocumentationAnnotationTransfer $annotationTransfer): void
    {
        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();

        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        if (!$pathDataTransfer->getSummary()) {
            $pathDataTransfer->setSummary($this->getDefaultMethodSummary(static::PATTERN_SUMMARY_DELETE_RESOURCE, $plugin->getResourceType()));
        }

        $this->pathGenerator->addDeletePath($pathDataTransfer, $errorSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return void
     */
    protected function addGetCollectionPath(ResourceRoutePluginInterface $plugin, RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer): void
    {
        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $responseSchema = $this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin);

        if (!$pathMethodDataTransfer->getSummary()) {
            $pathMethodDataTransfer->setSummary($this->getDefaultMethodSummary(static::PATTERN_SUMMARY_GET_COLLECTION, $pathMethodDataTransfer->getResource()));
        }

        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $responseSchema, $errorSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return void
     */
    protected function addGetResourcePathWithoutId(ResourceRoutePluginInterface $plugin, RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer): void
    {
        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $responseSchema = $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin);

        if (!$pathMethodDataTransfer->getSummary()) {
            $pathMethodDataTransfer->setSummary($this->getDefaultMethodSummary(static::PATTERN_SUMMARY_GET_RESOURCE, $pathMethodDataTransfer->getResource()));
        }

        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $responseSchema, $errorSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param string $idResource
     *
     * @return void
     */
    protected function addGetResourceWithId(ResourceRoutePluginInterface $plugin, RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer, string $idResource): void
    {
        $errorSchema = $this->schemaGenerator->getRestErrorSchemaData();
        $responseSchema = $this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin);

        if (!$pathMethodDataTransfer->getSummary()) {
            $pathMethodDataTransfer->setSummary($this->getDefaultMethodSummary(static::PATTERN_SUMMARY_GET_RESOURCE, $pathMethodDataTransfer->getResource()));
        }
        $pathMethodDataTransfer->setPath($pathMethodDataTransfer->getPath() . '/' . $idResource);

        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $responseSchema, $errorSchema);
    }

    /**
     * @param string $resource
     * @param string $path
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchema
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer
     */
    protected function createPathDataTransfer(
        string $resource,
        string $path,
        bool $isProtected,
        RestApiDocumentationPathSchemaDataTransfer $errorSchema,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
    ): RestApiDocumentationPathMethodDataTransfer {
        $pathDataTransfer = new RestApiDocumentationPathMethodDataTransfer();
        $pathDataTransfer->setResource($resource);
        $pathDataTransfer->setPath($path);
        $pathDataTransfer->setIsProtected($isProtected);

        if ($annotationTransfer) {
            $pathDataTransfer->fromArray($annotationTransfer->modifiedToArray(), true);
            $this->addResponsesToPathData($pathDataTransfer, $errorSchema, $annotationTransfer->getResponses());
        }

        return $pathDataTransfer;
    }

    /**
     * @param string $pattern
     * @param string $resourceType
     *
     * @return string
     */
    protected function getDefaultMethodSummary(string $pattern, string $resourceType): string
    {
        return sprintf($pattern, str_replace('-', ' ', $resourceType));
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
}
