<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Processor;

use Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationPathGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSchemaGeneratorInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSecuritySchemeGeneratorInterface;

class RestApiMethodProcessor implements RestApiMethodProcessorInterface
{
    protected const PATTERN_SUMMARY_GET_RESOURCE = 'Get %s';
    protected const PATTERN_SUMMARY_GET_COLLECTION = 'Get collection of %s';
    protected const PATTERN_SUMMARY_POST_RESOURCE = 'Create %s';
    protected const PATTERN_SUMMARY_PATCH_RESOURCE = 'Update %s';
    protected const PATTERN_SUMMARY_DELETE_RESOURCE = 'Delete %s';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationPathGeneratorInterface
     */
    protected $pathGenerator;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSchemaGeneratorInterface
     */
    protected $schemaGenerator;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSecuritySchemeGeneratorInterface
     */
    protected $securitySchemeGenerator;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationPathGeneratorInterface $pathGenerator
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSchemaGeneratorInterface $schemaGenerator
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\OpenApiSpecificationSecuritySchemeGeneratorInterface $securitySchemeGenerator
     */
    public function __construct(
        OpenApiSpecificationPathGeneratorInterface $pathGenerator,
        OpenApiSpecificationSchemaGeneratorInterface $schemaGenerator,
        OpenApiSpecificationSecuritySchemeGeneratorInterface $securitySchemeGenerator
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
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addGetResourcePath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        string $idResource,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());
        $pathDataTransfer = $this->createPathDataTransfer(
            $plugin->getResourceType(),
            $resourcePath,
            $isProtected,
            $errorSchema,
            $annotationTransfer
        );

        $this->addGetResource($plugin, $pathDataTransfer, $annotationTransfer);
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
    public function addGetResourceByIdPath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        string $idResource,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
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
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addGetResourceCollectionPath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        string $idResource,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
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
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addPostResourcePath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
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
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addPatchResourcePath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
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
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addDeleteResourcePath(
        ResourceRoutePluginInterface $plugin,
        string $resourcePath,
        bool $isProtected,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
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
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addGetCollectionPath(
        ResourceRoutePluginInterface $plugin,
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
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
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addGetResource(
        ResourceRoutePluginInterface $plugin,
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
    ): void {
        $errorSchema = $this->createPathSchemaDataTransfer($this->schemaGenerator->getRestErrorSchemaData());
        $responseSchema = $this->findResponseResourceSchema($plugin, $annotationTransfer);

        if (!$pathMethodDataTransfer->getSummary()) {
            $pathMethodDataTransfer->setSummary(
                $this->getDefaultMethodSummary(static::PATTERN_SUMMARY_GET_RESOURCE, $pathMethodDataTransfer->getResource())
            );
        }

        $this->pathGenerator->addGetPath($pathMethodDataTransfer, $errorSchema, $responseSchema);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param string $idResource
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    protected function addGetResourceById(
        ResourceRoutePluginInterface $plugin,
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        string $idResource,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
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
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchema
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer
     */
    protected function createPathDataTransfer(
        string $resource,
        string $path,
        bool $isProtected,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchema,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
    ): OpenApiSpecificationPathMethodDataTransfer {
        $pathDataTransfer = new OpenApiSpecificationPathMethodDataTransfer();
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
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer
     */
    protected function createPathSchemaDataTransfer(string $schemaRef): OpenApiSpecificationPathSchemaDataTransfer
    {
        $schemaDataTransfer = new OpenApiSpecificationPathSchemaDataTransfer();
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
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer|null
     */
    protected function findResponseResourceSchema(
        ResourceRoutePluginInterface $plugin,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
    ): ?OpenApiSpecificationPathSchemaDataTransfer {
        if (!$annotationTransfer) {
            return $this->createPathSchemaDataTransfer($this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin));
        }

        if ($annotationTransfer->getIsEmptyResponse()) {
            return null;
        }

        return $this->createPathSchemaDataTransfer(
            $this->schemaGenerator->addResponseResourceSchemaForPlugin($plugin, $annotationTransfer->getResponseClass())
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer|null
     */
    protected function findResponseCollectionSchema(
        ResourceRoutePluginInterface $plugin,
        ?RestApiDocumentationAnnotationTransfer $annotationTransfer
    ): ?OpenApiSpecificationPathSchemaDataTransfer {
        if (!$annotationTransfer) {
            return $this->createPathSchemaDataTransfer($this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin));
        }

        if ($annotationTransfer->getIsEmptyResponse()) {
            return null;
        }

        return $this->createPathSchemaDataTransfer(
            $this->schemaGenerator->addResponseCollectionSchemaForPlugin($plugin, $annotationTransfer->getResponseClass())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     * @param array $responses
     *
     * @return void
     */
    protected function addResponsesToPathData(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer,
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
