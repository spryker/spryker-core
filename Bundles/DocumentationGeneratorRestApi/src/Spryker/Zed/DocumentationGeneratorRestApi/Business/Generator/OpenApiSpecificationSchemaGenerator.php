<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\SchemaDataTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaBuilderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidTransferClassException;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRendererInterface;

class OpenApiSpecificationSchemaGenerator implements SchemaGeneratorInterface
{
    protected const KEY_IS_TRANSFER = 'is_transfer';
    protected const KEY_REST_REQUEST_PARAMETER = 'rest_request_parameter';
    protected const KEY_TYPE = 'type';
    protected const MESSAGE_INVALID_TRANSFER_CLASS = 'Invalid transfer class provided in plugin %s';
    protected const PATTERN_SCHEMA_REFERENCE = '#/components/schemas/%s';
    protected const REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED = 'no';

    /**
     * @var array
     */
    protected $schemas = [];

    /**
     * @var string
     */
    protected $restErrorSchemaReference;

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
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRendererInterface
     */
    protected $schemaRenderer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaBuilderInterface $schemaBuilder
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRendererInterface $schemaRenderer
     */
    public function __construct(
        ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer,
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        SchemaBuilderInterface $schemaBuilder,
        SchemaRendererInterface $schemaRenderer
    ) {
        $this->resourceRelationshipPluginAnalyzer = $resourceRelationshipPluginAnalyzer;
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
        $this->schemaBuilder = $schemaBuilder;
        $this->schemaRenderer = $schemaRenderer;

        $this->addDefaultSchemas();
    }

    /**
     * @return array
     */
    public function getSchemas(): array
    {
        ksort($this->schemas);

        return $this->schemas;
    }

    /**
     * @return string
     */
    public function getRestErrorSchemaData(): string
    {
        return $this->restErrorSchemaReference;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return string
     */
    public function addRequestSchemaForPlugin(ResourceRoutePluginInterface $plugin): string
    {
        $transferClassName = $this->resolveTransferClassNameForPlugin($plugin);
        if (!$this->isRequestSchemaRequired($transferClassName)) {
            return '';
        }

        $requestSchemaName = $this->resourceTransferAnalyzer->createRequestSchemaNameFromTransferClassName($transferClassName);
        $requestDataSchemaName = $this->resourceTransferAnalyzer->createRequestDataSchemaNameFromTransferClassName($transferClassName);
        $requestAttributesSchemaName = $this->resourceTransferAnalyzer->createRequestAttributesSchemaNameFromTransferClassName($transferClassName);

        $this->addSchemaData($this->schemaBuilder->createRequestBaseSchema($requestSchemaName, $requestDataSchemaName));
        $this->addSchemaData($this->schemaBuilder->createRequestDataSchema($requestDataSchemaName, $requestAttributesSchemaName));
        $this->addRequestDataAttributesSchemaFromTransfer(new $transferClassName(), $requestAttributesSchemaName);

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $requestSchemaName);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return string
     */
    public function addResponseResourceSchemaForPlugin(ResourceRoutePluginInterface $plugin, ?AnnotationTransfer $annotationTransfer = null): string
    {
        $transferClassName = $this->resolveTransferClassNameForPlugin($plugin, $annotationTransfer);
        $resourceRelationships = $this->resourceRelationshipPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);

        $responseSchemaName = $this->resourceTransferAnalyzer->createResponseResourceSchemaNameFromTransferClassName($transferClassName);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName($transferClassName);
        $responseAttributesSchemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($transferClassName);

        $isIdNullable = $annotationTransfer ? (bool)$annotationTransfer->getIsIdNullable() : false;
        $this->addSchemaData($this->schemaBuilder->createResponseBaseSchema($responseSchemaName, $responseDataSchemaName));
        $this->addSchemaData($this->schemaBuilder->createResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName, $isIdNullable));
        $this->addResponseDataAttributesSchemaFromTransfer(new $transferClassName(), $responseAttributesSchemaName);
        if ($resourceRelationships) {
            $this->addRelationshipSchemas($responseDataSchemaName, $resourceRelationships, $transferClassName);
        }

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $responseSchemaName);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param \Generated\Shared\Transfer\AnnotationTransfer|null $annotationTransfer
     *
     * @return string
     */
    public function addResponseCollectionSchemaForPlugin(ResourceRoutePluginInterface $plugin, ?AnnotationTransfer $annotationTransfer = null): string
    {
        $transferClassName = $this->resolveTransferClassNameForPlugin($plugin, $annotationTransfer);
        $resourceRelationships = $this->resourceRelationshipPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);

        $responseSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionSchemaNameFromTransferClassName($transferClassName);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionDataSchemaNameFromTransferClassName($transferClassName);
        $responseAttributesSchemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($transferClassName);

        $isIdNullable = $annotationTransfer ? (bool)$annotationTransfer->getIsIdNullable() : false;
        $this->addSchemaData($this->schemaBuilder->createCollectionResponseBaseSchema($responseSchemaName, $responseDataSchemaName));
        $this->addSchemaData($this->schemaBuilder->createResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName, $isIdNullable));
        $this->addResponseDataAttributesSchemaFromTransfer(new $transferClassName(), $responseAttributesSchemaName);
        if ($resourceRelationships) {
            $this->addRelationshipSchemas($responseDataSchemaName, $resourceRelationships, $transferClassName);
        }

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $responseSchemaName);
    }

    /**
     * @param string $responseDataSchemaName
     * @param array $resourceRelationships
     * @param string $transferClassName
     *
     * @return void
     */
    protected function addRelationshipSchemas(string $responseDataSchemaName, array $resourceRelationships, string $transferClassName): void
    {
        $resourceRelationshipsSchemaName = $this->resourceTransferAnalyzer->createResourceRelationshipSchemaNameFromTransferClassName($transferClassName);
        $this->addSchemaData($this->schemaBuilder->createRelationshipBaseSchema($responseDataSchemaName, $resourceRelationshipsSchemaName));
        $this->addSchemaData($this->schemaBuilder->createRelationshipDataSchema($resourceRelationshipsSchemaName, $resourceRelationships));
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param string $attributesSchemaName
     *
     * @return void
     */
    protected function addResponseDataAttributesSchemaFromTransfer(AbstractTransfer $transfer, string $attributesSchemaName): void
    {
        if (array_key_exists($attributesSchemaName, $this->schemas)) {
            return;
        }
        $this->schemas[$attributesSchemaName] = [];

        $transferMetadata = $this->resourceTransferAnalyzer->getTransferMetadata($transfer);
        foreach ($transferMetadata as $key => $value) {
            if ($value[static::KEY_IS_TRANSFER] && class_exists($value[static::KEY_TYPE])) {
                $schemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($value[static::KEY_TYPE]);
                $this->addResponseDataAttributesSchemaFromTransfer(new $value[static::KEY_TYPE](), $schemaName);
            }
        }

        $this->addSchemaData($this->schemaBuilder->createResponseDataAttributesSchema($attributesSchemaName, $transferMetadata));
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param string $attributesSchemaName
     *
     * @return void
     */
    protected function addRequestDataAttributesSchemaFromTransfer(AbstractTransfer $transfer, string $attributesSchemaName): void
    {
        if (array_key_exists($attributesSchemaName, $this->schemas)) {
            return;
        }
        $this->schemas[$attributesSchemaName] = [];

        $transferMetadata = $this->resourceTransferAnalyzer->getTransferMetadata($transfer);
        foreach ($transferMetadata as $property) {
            if ($property[static::KEY_IS_TRANSFER] && $property[static::KEY_REST_REQUEST_PARAMETER] !== static::REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED) {
                $schemaName = $this->resourceTransferAnalyzer->createRequestAttributesSchemaNameFromTransferClassName($property[static::KEY_TYPE]);
                $this->addRequestDataAttributesSchemaFromTransfer(new $property[static::KEY_TYPE](), $schemaName);
            }
        }

        $this->addSchemaData($this->schemaBuilder->createRequestDataAttributesSchema($attributesSchemaName, $transferMetadata));
    }

    /**
     * @return void
     */
    protected function addDefaultSchemas(): void
    {
        $this->addDefaultErrorMessageSchema();
        $this->addDefaultLinksSchema();
        $this->addDefaultRelationshipsSchema();
    }

    /**
     * @return void
     */
    protected function addDefaultErrorMessageSchema(): void
    {
        $restErrorSchemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName(RestErrorMessageTransfer::class);
        $this->addResponseDataAttributesSchemaFromTransfer(new RestErrorMessageTransfer(), $restErrorSchemaName);

        $this->restErrorSchemaReference = sprintf(static::PATTERN_SCHEMA_REFERENCE, $restErrorSchemaName);
    }

    /**
     * @return void
     */
    protected function addDefaultLinksSchema(): void
    {
        $this->addSchemaData($this->schemaBuilder->createDefaultLinksSchema());
    }

    /**
     * @return void
     */
    protected function addDefaultRelationshipsSchema(): void
    {
        $this->addSchemaData($this->schemaBuilder->createDefaultRelationshipDataAttributesSchema());
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaDataTransfer $schemaData
     *
     * @return void
     */
    protected function addSchemaData(SchemaDataTransfer $schemaData): void
    {
        $this->schemas = array_replace_recursive($this->schemas, $this->schemaRenderer->render($schemaData));
    }

    /**
     * @param string $transferClassName
     *
     * @return bool
     */
    protected function isRequestSchemaRequired(string $transferClassName): bool
    {
        $transferMetadata = $this->resourceTransferAnalyzer->getTransferMetadata(new $transferClassName());
        foreach ($transferMetadata as $metadataParameter) {
            if ($metadataParameter[static::KEY_REST_REQUEST_PARAMETER] !== static::REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED) {
                return true;
            }
        }

        return false;
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
        $this->validatePluginTransfer($plugin, $transferClassName);

        return $transferClassName;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $transferClassName
     *
     * @throws \Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidTransferClassException
     *
     * @return void
     */
    protected function validatePluginTransfer(ResourceRoutePluginInterface $plugin, string $transferClassName): void
    {
        if (!$this->resourceTransferAnalyzer->isTransferValid($transferClassName)) {
            throw new InvalidTransferClassException(sprintf(static::MESSAGE_INVALID_TRANSFER_CLASS, get_class($plugin)));
        }
    }
}
