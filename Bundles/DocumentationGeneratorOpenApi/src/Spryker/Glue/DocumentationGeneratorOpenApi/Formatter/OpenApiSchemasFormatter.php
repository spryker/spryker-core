<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter;

use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\PathAnnotationTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\SchemaComponentTransfer;
use Generated\Shared\Transfer\SchemaDataTransfer;
use Generated\Shared\Transfer\SchemaItemsComponentTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Exception\InvalidTransferClassException;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Builder\SchemaBuilderInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Processor\ResourceRelationshipProcessorInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\SchemaRendererInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class OpenApiSchemasFormatter implements OpenApiSchemaFormatterInterface
{
    /**
     * @var string
     */
    protected const KEY_IS_TRANSFER = 'is_transfer';

    /**
     * @var string
     */
    protected const KEY_TYPE = 'type';

    /**
     * @var string
     */
    protected const PATTERN_SCHEMA_REFERENCE = '#/components/schemas/%s';

    /**
     * @var array<mixed>
     */
    protected $schemas = [];

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected $resourceTransferAnalyzer;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Builder\SchemaBuilderInterface
     */
    protected $schemaBuilder;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\SchemaRendererInterface
     */
    protected $schemaRenderer;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Processor\ResourceRelationshipProcessorInterface
     */
    protected $resourceRelationshipProcessor;

    /**
     * @var array<string, array<string>>
     */
    protected $relationshipMap;

    /**
     * @var array<string, array<string>>
     */
    protected $resourceResponsesMap;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Builder\SchemaBuilderInterface $schemaBuilder
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\SchemaRendererInterface $schemaRenderer
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Processor\ResourceRelationshipProcessorInterface $resourceRelationshipProcessor
     */
    public function __construct(
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        SchemaBuilderInterface $schemaBuilder,
        SchemaRendererInterface $schemaRenderer,
        ResourceRelationshipProcessorInterface $resourceRelationshipProcessor
    ) {
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
        $this->schemaBuilder = $schemaBuilder;
        $this->schemaRenderer = $schemaRenderer;
        $this->resourceRelationshipProcessor = $resourceRelationshipProcessor;

        $this->addDefaultSchemas();
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(
        array $formattedData,
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
    ): array {
        $this->fillRelationsMap($apiApplicationSchemaContextTransfer->getRelationshipPluginsContexts()->getArrayCopy());

        $this->processResourceContexts($apiApplicationSchemaContextTransfer->getResourceContexts()->getArrayCopy());
        $this->processCustomRouteContexts($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->getArrayCopy());

        ksort($this->schemas);
        $formattedData['components']['schemas'] = $this->schemas;

        return $formattedData;
    }

    /**
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     *
     * @return string
     */
    protected function addRequestSchema(AnnotationTransfer $annotationTransfer): string
    {
        /** @phpstan-var class-string<\Spryker\Shared\Kernel\Transfer\AbstractTransfer> $transferClassName */
        $transferClassName = $this->resolveTransferClassName($annotationTransfer);
        if (!$this->resourceTransferAnalyzer->isRequestSchemaRequired($transferClassName)) {
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
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     * @param \Generated\Shared\Transfer\ResourceContextTransfer|null $resourceContextTransfer
     *
     * @return string
     */
    protected function addResponseResourceSchema(AnnotationTransfer $annotationTransfer, ?ResourceContextTransfer $resourceContextTransfer): string
    {
        /** @phpstan-var class-string<\Spryker\Shared\Kernel\Transfer\AbstractTransfer> $transferClassName */
        $transferClassName = $this->resolveTransferClassName($annotationTransfer);

        $responseSchemaName = $this->resourceTransferAnalyzer->createResponseResourceSchemaNameFromTransferClassName($transferClassName);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName($transferClassName);
        $responseAttributesSchemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($transferClassName);

        $isIdNullable = (bool)$annotationTransfer->getIsIdNullable();
        $this->addSchemaData($this->schemaBuilder->createResponseBaseSchema($responseSchemaName, $responseDataSchemaName));
        $this->addSchemaData($this->schemaBuilder->createResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName, $isIdNullable));
        $this->addResponseDataAttributesSchemaFromTransfer(new $transferClassName(), $responseAttributesSchemaName);

        if ($resourceContextTransfer) {
            $relationShipResourceAttributesClassNames = $this->getRelationshipResourceAttributesClassNames($resourceContextTransfer);
            $relationships = $this->getRelationships($resourceContextTransfer);
            $this->addAttributesSchemasFromResourceRelationshipAnnotations($relationShipResourceAttributesClassNames);
            $this->addRelationshipSchemas($relationships, $transferClassName, $responseDataSchemaName);
            $this->addIncludeSchemas($relationShipResourceAttributesClassNames, $transferClassName, $responseSchemaName);
        }

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $responseSchemaName);
    }

    /**
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     * @param \Generated\Shared\Transfer\ResourceContextTransfer|null $resourceContextTransfer
     *
     * @return string
     */
    protected function addResponseCollectionSchema(AnnotationTransfer $annotationTransfer, ?ResourceContextTransfer $resourceContextTransfer): string
    {
        /** @phpstan-var class-string<\Spryker\Shared\Kernel\Transfer\AbstractTransfer> $transferClassName */
        $transferClassName = $this->resolveTransferClassName($annotationTransfer);

        $responseSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionSchemaNameFromTransferClassName($transferClassName);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionDataSchemaNameFromTransferClassName($transferClassName);
        $responseAttributesSchemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($transferClassName);

        $isIdNullable = (bool)$annotationTransfer->getIsIdNullable();
        $this->addSchemaData($this->schemaBuilder->createCollectionResponseBaseSchema($responseSchemaName, $responseDataSchemaName));
        $this->addSchemaData($this->schemaBuilder->createResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName, $isIdNullable));
        $this->addResponseDataAttributesSchemaFromTransfer(new $transferClassName(), $responseAttributesSchemaName);

        if ($resourceContextTransfer) {
            $relationShipResourceAttributesClassNames = $this->getRelationshipResourceAttributesClassNames($resourceContextTransfer);
            $relationships = $this->getRelationships($resourceContextTransfer);
            $this->addAttributesSchemasFromResourceRelationshipAnnotations($relationShipResourceAttributesClassNames);
            $this->addRelationshipSchemas($relationships, $transferClassName, $responseDataSchemaName);
            $this->addIncludeSchemas($relationShipResourceAttributesClassNames, $transferClassName, $responseSchemaName);
        }

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $responseSchemaName);
    }

    /**
     * @param array<string> $resourceAttributesClassNames
     *
     * @return void
     */
    protected function addAttributesSchemasFromResourceRelationshipAnnotations(array $resourceAttributesClassNames): void
    {
        if ($resourceAttributesClassNames === []) {
            return;
        }

        foreach ($resourceAttributesClassNames as $resourceAttributesClassName) {
            $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName($resourceAttributesClassName);
            $responseAttributesSchemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($resourceAttributesClassName);

            $this->addSchemaData($this->schemaBuilder->createResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName, false));
            /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer */
            $transfer = new $resourceAttributesClassName();
            $this->addResponseDataAttributesSchemaFromTransfer($transfer, $responseAttributesSchemaName);
        }
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
        foreach ($transferMetadata as $property) {
            if ($property[static::KEY_IS_TRANSFER]) {
                $this->validateTransfer($property[static::KEY_TYPE]);
                $schemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($property[static::KEY_TYPE]);
                /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer */
                $transfer = new $property[static::KEY_TYPE]();
                $this->addResponseDataAttributesSchemaFromTransfer($transfer, $schemaName);
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
            if ($property[static::KEY_IS_TRANSFER] && $this->resourceTransferAnalyzer->isRequestParameterRequired($property)) {
                $this->validateTransfer($property[static::KEY_TYPE]);
                $schemaName = $this->resourceTransferAnalyzer->createRequestAttributesSchemaNameFromTransferClassName($property[static::KEY_TYPE]);
                /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer */
                $transfer = new $property[static::KEY_TYPE]();
                $this->addRequestDataAttributesSchemaFromTransfer($transfer, $schemaName);
            }
        }

        $this->addSchemaData($this->schemaBuilder->createRequestDataAttributesSchema($attributesSchemaName, $transferMetadata));
    }

    /**
     * @param array<string> $relationships
     * @param string $transferClassName
     * @param string $responseDataSchemaName
     *
     * @return void
     */
    protected function addRelationshipSchemas(
        array $relationships,
        string $transferClassName,
        string $responseDataSchemaName
    ): void {
        $relationshipSchemaDataTransfers = $this
            ->resourceRelationshipProcessor
            ->getRelationshipSchemaDataTransfers($relationships, $transferClassName, $responseDataSchemaName);

        foreach ($relationshipSchemaDataTransfers as $relationshipSchemaDataTransfer) {
            $this->addSchemaData($relationshipSchemaDataTransfer);
        }
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
        $this->addResponseDataAttributesSchemaFromTransfer(
            new RestErrorMessageTransfer(),
            $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName(RestErrorMessageTransfer::class),
        );
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
        $this->addSchemaData($this->schemaBuilder->createDefaultRelationshipDataCollectionAttributesSchema());
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
     * @param \Generated\Shared\Transfer\SchemaDataTransfer $schemaData
     *
     * @return void
     */
    protected function addIncludeSchemaData(SchemaDataTransfer $schemaData): void
    {
        $renderData = $this->schemaRenderer->render($schemaData);
        foreach ($renderData as $key => $item) {
            if (!isset($this->schemas[$key])) {
                $this->schemas = array_replace_recursive($this->schemas, $renderData);

                continue;
            }
            $oneOfs = array_merge(
                $this->schemas[$key][SchemaComponentTransfer::ITEMS][SchemaItemsComponentTransfer::ONE_OF],
                $item[SchemaComponentTransfer::ITEMS][SchemaItemsComponentTransfer::ONE_OF],
            );
            $this->schemas[$key][SchemaComponentTransfer::ITEMS][SchemaItemsComponentTransfer::ONE_OF] = array_unique($oneOfs, SORT_REGULAR);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     *
     * @return string
     */
    protected function resolveTransferClassName(AnnotationTransfer $annotationTransfer): string
    {
        $transferClassName = $annotationTransfer->getResponseAttributesClassNameOrFail();
        $this->validateTransfer($transferClassName);

        return $transferClassName;
    }

    /**
     * @param string $transferClassName
     *
     * @throws \Spryker\Glue\DocumentationGeneratorOpenApi\Exception\InvalidTransferClassException
     *
     * @return void
     */
    protected function validateTransfer(string $transferClassName): void
    {
        if (!$this->resourceTransferAnalyzer->isTransferValid($transferClassName)) {
            throw new InvalidTransferClassException(
                sprintf('Invalid transfer %s', $transferClassName),
            );
        }
    }

    /**
     * @param array<string> $resourceAttributesClassNames
     * @param string $transferClassName
     * @param string $responseSchemaName
     *
     * @return void
     */
    protected function addIncludeSchemas(
        array $resourceAttributesClassNames,
        string $transferClassName,
        string $responseSchemaName
    ): void {
        if (!$resourceAttributesClassNames) {
            return;
        }

        $this->addSchemaData(
            $this
                ->resourceRelationshipProcessor
                ->getIncludeBaseSchema($transferClassName, $responseSchemaName),
        );

        $relationshipResponses = [];

        foreach ($resourceAttributesClassNames as $resourceAttributesClassName) {
            $relationshipResponses[] = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName(
                $resourceAttributesClassName,
            );
        }

        $this->addIncludeSchemaData(
            $this
                ->resourceRelationshipProcessor
                ->getIncludeDataSchema($transferClassName, $relationshipResponses),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\RelationshipPluginsContextTransfer> $relationshipPluginsContextTransfers
     *
     * @return void
     */
    protected function fillRelationsMap(array $relationshipPluginsContextTransfers): void
    {
        foreach ($relationshipPluginsContextTransfers as $relationshipPluginsContextTransfer) {
            $resourceType = $relationshipPluginsContextTransfer->getResourceTypeOrFail();
            $relationship = $relationshipPluginsContextTransfer->getRelationshipOrFail();
            $resourceAttributesClassName = $relationshipPluginsContextTransfer->getRelationshipPluginAnnotationsContextOrFail()->getResourceAttributesClassNameOrFail();
            $this->relationshipMap[$resourceType][$relationship] = $resourceAttributesClassName;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContextTransfer
     *
     * @return array<string, string>
     */
    protected function getRelationshipResourceAttributesClassNames(ResourceContextTransfer $resourceContextTransfer): array
    {
        if (!$resourceContextTransfer->getRelationships()) {
            return [];
        }

        $resourceAttributesClassName = [];
        $resourceTypeOrFail = $resourceContextTransfer->getResourceTypeOrFail();
        $relationships = $this->getRelationships($resourceContextTransfer);

        foreach ($relationships as $relationship) {
            $resourceAttributesClassName[$relationship] = $this->relationshipMap[$resourceTypeOrFail][$relationship];
        }

        return $resourceAttributesClassName;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContextTransfer
     *
     * @return array<string>
     */
    protected function getRelationships(ResourceContextTransfer $resourceContextTransfer): array
    {
        $relationships = $resourceContextTransfer->getRelationships();

        if (!$relationships) {
            return [];
        }

        return explode(',', $relationships);
    }

    /**
     * @param \Generated\Shared\Transfer\PathAnnotationTransfer $pathAnnotationTransfer
     *
     * @return array<\Generated\Shared\Transfer\AnnotationTransfer>
     */
    protected function getAnnotationsWithRequest(PathAnnotationTransfer $pathAnnotationTransfer): array
    {
        return array_filter([
            $pathAnnotationTransfer->getPost(),
            $pathAnnotationTransfer->getPatch(),
            $pathAnnotationTransfer->getDelete(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PathAnnotationTransfer $pathAnnotationTransfer
     *
     * @return array<\Generated\Shared\Transfer\AnnotationTransfer>
     */
    protected function getNotCollectionPathAnnotations(PathAnnotationTransfer $pathAnnotationTransfer): array
    {
        return array_filter([
            $pathAnnotationTransfer->getGetResourceById(),
            $pathAnnotationTransfer->getPost(),
            $pathAnnotationTransfer->getPatch(),
            $pathAnnotationTransfer->getDelete(),
        ]);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ResourceContextTransfer> $resourceContextTransfers
     *
     * @return void
     */
    protected function processResourceContexts(array $resourceContextTransfers): void
    {
        foreach ($resourceContextTransfers as $resourceContextTransfer) {
            $pathAnnotationTransfer = $resourceContextTransfer->getPathAnnotationOrFail();

            foreach ($this->getAnnotationsWithRequest($pathAnnotationTransfer) as $annotationTransfer) {
                $this->addRequestSchema($annotationTransfer);
            }
            foreach ($this->getNotCollectionPathAnnotations($pathAnnotationTransfer) as $annotationTransfer) {
                $this->addResponseResourceSchema($annotationTransfer, $resourceContextTransfer);
            }

            if ($resourceContextTransfer->getPathAnnotationOrFail()->getGetCollection()) {
                $this->addResponseCollectionSchema(
                    $resourceContextTransfer->getPathAnnotationOrFail()->getGetCollectionOrFail(),
                    $resourceContextTransfer,
                );
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\CustomRoutesContextTransfer> $customRoutesContextTransfers
     *
     * @return void
     */
    protected function processCustomRouteContexts(array $customRoutesContextTransfers): void
    {
        foreach ($customRoutesContextTransfers as $customRoutesContextTransfer) {
            if (isset($customRoutesContextTransfer->getDefaults()['_resourceName'])) {
                continue;
            }

            $pathAnnotationTransfer = $customRoutesContextTransfer->getPathAnnotationOrFail();

            foreach ($this->getAnnotationsWithRequest($pathAnnotationTransfer) as $annotationTransfer) {
                $this->addRequestSchema($annotationTransfer);
            }
            foreach ($this->getNotCollectionPathAnnotations($pathAnnotationTransfer) as $annotationTransfer) {
                $this->addResponseResourceSchema($annotationTransfer, null);
            }

            if ($pathAnnotationTransfer->getGetCollection()) {
                $this->addResponseCollectionSchema(
                    $customRoutesContextTransfer->getPathAnnotationOrFail()->getGetCollectionOrFail(),
                    null,
                );
            }
        }
    }
}
