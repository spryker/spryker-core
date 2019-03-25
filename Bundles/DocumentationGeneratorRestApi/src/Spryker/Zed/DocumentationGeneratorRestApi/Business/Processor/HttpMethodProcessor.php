<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor;

use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\PathMethodDataTransfer;
use Generated\Shared\Transfer\PathSchemaDataTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\PathGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SecuritySchemeGeneratorInterface;

class HttpMethodProcessor implements HttpMethodProcessorInterface
{
    protected const PATTERN_SUMMARY_GET_RESOURCE = 'Get %s.';
    protected const PATTERN_SUMMARY_GET_COLLECTION = 'Get collection of %s.';
    protected const PATTERN_SUMMARY_POST_RESOURCE = 'Create %s.';
    protected const PATTERN_SUMMARY_PATCH_RESOURCE = 'Update %s.';
    protected const PATTERN_SUMMARY_DELETE_RESOURCE = 'Delete %s.';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\PathGeneratorInterface
     */
    protected $pathGenerator;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface
     */
    protected $schemaGenerator;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SecuritySchemeGeneratorInterface
     */
    protected $securitySchemeGenerator;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\PathGeneratorInterface $pathGenerator
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SchemaGeneratorInterface $schemaGenerator
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SecuritySchemeGeneratorInterface $securitySchemeGenerator
     */
    public function __construct(
        PathGeneratorInterface $pathGenerator,
        SchemaGeneratorInterface $schemaGenerator,
        SecuritySchemeGeneratorInterface $securitySchemeGenerator
    ) {
        $this->pathGenerator = $pathGenerator;
        $this->schemaGenerator = $schemaGenerator;
        $this->securitySchemeGenerator = $securitySchemeGenerator;
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
        return $this->securitySchemeGenerator->getSecuritySchemes();
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param string $idResource
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addGetResourceByIdPath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        string $idResource,
        ?AnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());
        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        $this->addGetResourceById($plugin, $pathDataTransfer, $idResource, $annotationTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param string $idResource
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addGetResourceCollectionPath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        string $idResource,
        ?AnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());
        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        $this->addGetCollectionPath($plugin, $pathDataTransfer, $annotationTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addPostResourcePath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        ?AnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());
        $responseSchema = $this->findResponseResourceSchema($plugin, $annotationTransfer);
        $requestSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->addRequestSchemaForPlugin($plugin));

        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        if (!$pathDataTransfer->getSummary()) {
            $pathDataTransfer->setSummary(
                $this->getDefaultMethodSummary(static::PATTERN_SUMMARY_POST_RESOURCE, $plugin->getResourceType())
            );
        }

        $this->pathGenerator->addPostPath($pathDataTransfer, $requestSchema, $errorSchema, $responseSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addPatchResourcePath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        ?AnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());
        $responseSchema = $this->findResponseResourceSchema($plugin, $annotationTransfer);
        $requestSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->addRequestSchemaForPlugin($plugin));

        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        if (!$pathDataTransfer->getSummary()) {
            $pathDataTransfer->setSummary(
                $this->getDefaultMethodSummary(static::PATTERN_SUMMARY_PATCH_RESOURCE, $plugin->getResourceType())
            );
        }

        $this->pathGenerator->addPatchPath($pathDataTransfer, $requestSchema, $errorSchema, $responseSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addDeleteResourcePath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        ?AnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());

        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        if (!$pathDataTransfer->getSummary()) {
            $pathDataTransfer->setSummary(
                $this->getDefaultMethodSummary(static::PATTERN_SUMMARY_DELETE_RESOURCE, $plugin->getResourceType())
            );
        }

        $this->pathGenerator->addDeletePath($pathDataTransfer, $errorSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addGetCollectionPath(
        ResourceRoutePluginInterface $plugin,
        PathMethodDataTransfer $pathMethodDataTransfer,
        ?AnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());
        $responseSchema = $this->findResponseCollectionSchema($plugin, $annotationTransfer);

        if (!$pathMethodDataTransfer->getSummary()) {
            $pathMethodDataTransfer->setSummary(
                $this->getDefaultMethodSummary(static::PATTERN_SUMMARY_GET_COLLECTION, $pathMethodDataTransfer->getResource())
            );
        }

        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $errorSchema, $responseSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param string $idResource
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addGetResourceById(
        ResourceRoutePluginInterface $plugin,
        PathMethodDataTransfer $pathMethodDataTransfer,
        string $idResource,
        ?AnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());
        $responseSchema = $this->findResponseResourceSchema($plugin, $annotationTransfer);

        if (!$pathMethodDataTransfer->getSummary()) {
            $pathMethodDataTransfer->setSummary(
                $this->getDefaultMethodSummary(static::PATTERN_SUMMARY_GET_RESOURCE, $pathMethodDataTransfer->getResource())
            );
        }
        $pathMethodDataTransfer->setPath($pathMethodDataTransfer->getPath() . '/' . $idResource);

        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $errorSchema, $responseSchema);
    }

    /**
     * @param string $resource
     * @param string $path
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchema
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return \Generated\Shared\Transfer\PathMethodDataTransfer
     */
    protected function createPathDataTransfer(
        string $resource,
        string $path,
        bool $isProtected,
        PathSchemaDataTransfer $errorSchema,
        ?AnnotationTransfer $annotationTransfer
    ): PathMethodDataTransfer {
        $pathDataTransfer = new PathMethodDataTransfer();
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
     * @param string $schemaRef
     *
     * @return \Generated\Shared\Transfer\PathSchemaDataTransfer
     */
    protected function createPathSchemaDataTransfer(string $schemaRef): PathSchemaDataTransfer
    {
        $schemaDataTransfer = new PathSchemaDataTransfer();
        $schemaDataTransfer->setSchemaReference($schemaRef);

        return $schemaDataTransfer;
    }

    /**
     * @param string $pattern
     * @param string $resourceType
     *
     * @return string[]
     */
    protected function getDefaultMethodSummary(string $pattern, string $resourceType): array
    {
        return [sprintf($pattern, str_replace('-', ' ', $resourceType))];
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return \Generated\Shared\Transfer\PathSchemaDataTransfer|null
     */
    protected function findResponseResourceSchema(
        ResourceRoutePluginInterface $plugin,
        ?AnnotationTransfer $annotationTransfer
    ): ?PathSchemaDataTransfer {
        if (!$annotationTransfer) {
            return $this->createPathSchemaDataTransfer($this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin));
        }

        if ($annotationTransfer->getIsEmptyResponse()) {
            return null;
        }

        return $this->createPathSchemaDataTransfer(
            $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin, $annotationTransfer)
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return \Generated\Shared\Transfer\PathSchemaDataTransfer|null
     */
    protected function findResponseCollectionSchema(
        ResourceRoutePluginInterface $plugin,
        ?AnnotationTransfer $annotationTransfer
    ): ?PathSchemaDataTransfer {
        if (!$annotationTransfer) {
            return $this->createPathSchemaDataTransfer($this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin));
        }

        if ($annotationTransfer->getIsEmptyResponse()) {
            return null;
        }

        return $this->createPathSchemaDataTransfer(
            $this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin, $annotationTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     * @param array $responses
     *
     * @return void
     */
    protected function addResponsesToPathData(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer,
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
