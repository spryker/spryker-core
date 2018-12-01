<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\SchemaDataTransfer;
use Generated\Shared\Transfer\SchemaPropertyTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidTransferClassException;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRendererInterface;

class OpenApiSpecificationSchemaGenerator implements SchemaGeneratorInterface
{
    protected const KEY_ATTRIBUTES = 'attributes';
    protected const KEY_DATA = 'data';
    protected const KEY_ID = 'id';
    protected const KEY_LINKS = 'links';
    protected const KEY_RELATIONSHIPS = 'relationships';
    protected const KEY_REST_REQUEST_PARAMETER = 'rest_request_parameter';
    protected const KEY_SELF = 'self';
    protected const KEY_TYPE = 'type';
    protected const VALUE_TYPE_STRING = 'string';

    protected const PATTERN_SCHEMA_REFERENCE = '#/components/schemas/%s';

    protected const SCHEMA_NAME_LINKS = 'RestLinks';
    protected const SCHEMA_NAME_RELATIONSHIPS = 'RestRelationships';

    protected const REST_REQUEST_BODY_PARAMETER_REQUIRED = 'required';
    protected const REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED = 'no';

    protected const MESSAGE_INVALID_TRANSFER_CLASS = 'Invalid transfer class provided in plugin %s';

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
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface
     */
    protected $schemaComponentBuilder;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRendererInterface
     */
    protected $schemaRenderer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface $schemaComponentBuilder
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SchemaRendererInterface $schemaRenderer
     */
    public function __construct(
        ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer,
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        SchemaComponentBuilderInterface $schemaComponentBuilder,
        SchemaRendererInterface $schemaRenderer
    ) {
        $this->resourceRelationshipPluginAnalyzer = $resourceRelationshipPluginAnalyzer;
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
        $this->schemaComponentBuilder = $schemaComponentBuilder;
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
     * @throws \Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidTransferClassException
     *
     * @return string
     */
    public function addRequestSchemaForPlugin(ResourceRoutePluginInterface $plugin): string
    {
        $transferClassName = $plugin->getResourceAttributesClassName();
        if (!$this->resourceTransferAnalyzer->isTransferValid($transferClassName)) {
            throw new InvalidTransferClassException(sprintf(static::MESSAGE_INVALID_TRANSFER_CLASS, get_class($plugin)));
        }

        $requestSchemaName = $this->resourceTransferAnalyzer->createRequestSchemaNameFromTransferClassName($transferClassName);
        $requestDataSchemaName = $this->resourceTransferAnalyzer->createRequestDataSchemaNameFromTransferClassName($transferClassName);
        $requestAttributesSchemaName = $this->resourceTransferAnalyzer->createRequestAttributesSchemaNameFromTransferClassName($transferClassName);

        $this->addRequestBaseSchema($requestSchemaName, $requestDataSchemaName);
        $this->addRequestDataSchema($requestDataSchemaName, $requestAttributesSchemaName);
        $this->addRequestDataAttributesSchemaFromTransfer(new $transferClassName(), $requestAttributesSchemaName);

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $requestSchemaName);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string|null $transferClassName
     *
     * @throws \Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidTransferClassException
     *
     * @return string
     */
    public function addResponseResourceSchemaForPlugin(ResourceRoutePluginInterface $plugin, ?string $transferClassName = null): string
    {
        if (!$transferClassName) {
            $transferClassName = $plugin->getResourceAttributesClassName();
        }

        if (!$this->resourceTransferAnalyzer->isTransferValid($transferClassName)) {
            throw new InvalidTransferClassException(sprintf(static::MESSAGE_INVALID_TRANSFER_CLASS, get_class($plugin)));
        }

        $resourceRelationships = $this->resourceRelationshipPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);

        $responseSchemaName = $this->resourceTransferAnalyzer->createResponseResourceSchemaNameFromTransferClassName($transferClassName);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseResourceDataSchemaNameFromTransferClassName($transferClassName);
        $responseAttributesSchemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($transferClassName);

        $this->addResponseSchemas($responseSchemaName, $responseDataSchemaName, $responseAttributesSchemaName, new $transferClassName());
        if ($resourceRelationships) {
            $this->addRelationshipSchemas($responseDataSchemaName, $resourceRelationships, $transferClassName);
        }

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $responseSchemaName);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string|null $transferClassName
     *
     * @throws \Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidTransferClassException
     *
     * @return string
     */
    public function addResponseCollectionSchemaForPlugin(ResourceRoutePluginInterface $plugin, ?string $transferClassName = null): string
    {
        if (!$transferClassName) {
            $transferClassName = $plugin->getResourceAttributesClassName();
        }

        if (!$this->resourceTransferAnalyzer->isTransferValid($transferClassName)) {
            throw new InvalidTransferClassException(sprintf(static::MESSAGE_INVALID_TRANSFER_CLASS, get_class($plugin)));
        }

        $resourceRelationships = $this->resourceRelationshipPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);

        $responseSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionSchemaNameFromTransferClassName($transferClassName);
        $responseDataSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionDataSchemaNameFromTransferClassName($transferClassName);
        $responseAttributesSchemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($transferClassName);

        $this->addCollectionResponseSchemas($responseSchemaName, $responseDataSchemaName, $responseAttributesSchemaName, new $transferClassName());
        if ($resourceRelationships) {
            $this->addRelationshipSchemas($responseDataSchemaName, $resourceRelationships, $transferClassName);
        }

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $responseSchemaName);
    }

    /**
     * @param string $responseSchemaName
     * @param string $responseDataSchemaName
     * @param string $responseAttributesSchemaName
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return void
     */
    protected function addResponseSchemas(string $responseSchemaName, string $responseDataSchemaName, string $responseAttributesSchemaName, AbstractTransfer $transfer): void
    {
        $this->addResponseBaseSchema($responseSchemaName, $responseDataSchemaName);
        $this->addResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName);
        $this->addResponseDataAttributesSchemaFromTransfer($transfer, $responseAttributesSchemaName);
    }

    /**
     * @param string $responseSchemaName
     * @param string $responseDataSchemaName
     * @param string $responseAttributesSchemaName
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return void
     */
    protected function addCollectionResponseSchemas(string $responseSchemaName, string $responseDataSchemaName, string $responseAttributesSchemaName, AbstractTransfer $transfer): void
    {
        $this->addCollectionResponseBaseSchema($responseSchemaName, $responseDataSchemaName);
        $this->addResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName);
        $this->addResponseDataAttributesSchemaFromTransfer($transfer, $responseAttributesSchemaName);
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
        $this->addRelationshipsDataToResponseDataSchema($responseDataSchemaName, $resourceRelationshipsSchemaName, $resourceRelationships);
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

        $transferMetadataValue = $this->resourceTransferAnalyzer->getTransferMetadata($transfer);
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($attributesSchemaName);
        foreach ($transferMetadataValue as $key => $value) {
            $schemaData->addProperty($this->createSchemaPropertyTransfer($key, $value));
        }

        $this->addSchemaData($schemaData);
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
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($attributesSchemaName);
        foreach ($transferMetadata as $key => $value) {
            if ($value[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED) {
                continue;
            }
            if ($value[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_REQUIRED) {
                $schemaData->addRequired($key);
            }
            $schemaData->addProperty($this->createSchemaPropertyTransfer($key, $value));
        }

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    protected function addRequestBaseSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_DATA, $ref));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    protected function addRequestDataSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_TYPE_STRING));
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_ATTRIBUTES, $ref));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    protected function addResponseBaseSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_DATA, $ref));
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_LINKS, static::SCHEMA_NAME_LINKS));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    protected function addResponseDataSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_TYPE_STRING));
        $schemaData->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_ID, static::VALUE_TYPE_STRING));
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_ATTRIBUTES, $ref));
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_LINKS, static::SCHEMA_NAME_LINKS));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    protected function addCollectionResponseBaseSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createArrayOfObjectsPropertyTransfer(static::KEY_DATA, $ref));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $dataSchemaName
     * @param string $relationshipsSchemaName
     * @param array $resourceRelationships
     *
     * @return void
     */
    protected function addRelationshipsDataToResponseDataSchema(string $dataSchemaName, string $relationshipsSchemaName, array $resourceRelationships): void
    {
        $dataSchemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($dataSchemaName);
        $dataSchemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_RELATIONSHIPS, $relationshipsSchemaName));
        $this->addSchemaData($dataSchemaData);

        $relationshipsSchemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($relationshipsSchemaName);
        foreach ($resourceRelationships as $resourceRelationship) {
            $relationshipsSchemaData->addProperty($this->schemaComponentBuilder->createArrayOfObjectsPropertyTransfer($resourceRelationship, static::SCHEMA_NAME_RELATIONSHIPS));
        }
        $this->addSchemaData($relationshipsSchemaData);
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
        $linksSchema = $this->schemaComponentBuilder->createSchemaDataTransfer(static::SCHEMA_NAME_LINKS);
        $linksSchema->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_SELF, static::VALUE_TYPE_STRING));

        $this->addSchemaData($linksSchema);
    }

    /**
     * @return void
     */
    protected function addDefaultRelationshipsSchema(): void
    {
        $relationshipsSchema = $this->schemaComponentBuilder->createSchemaDataTransfer(static::SCHEMA_NAME_RELATIONSHIPS);
        $relationshipsSchema->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_ID, static::VALUE_TYPE_STRING));
        $relationshipsSchema->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_TYPE_STRING));

        $this->addSchemaData($relationshipsSchema);
    }

    /**
     * @param string $metadataKey
     * @param array $metadataValue
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    protected function createSchemaPropertyTransfer(string $metadataKey, array $metadataValue): SchemaPropertyTransfer
    {
        if (class_exists($metadataValue[static::KEY_TYPE])) {
            $schemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($metadataValue[static::KEY_TYPE]);
            $this->addResponseDataAttributesSchemaFromTransfer(new $metadataValue[static::KEY_TYPE](), $schemaName);

            return $this->schemaComponentBuilder->createObjectSchemaTypeTransfer($metadataKey, $schemaName, $metadataValue);
        }

        return $this->schemaComponentBuilder->createScalarSchemaTypeTransfer($metadataKey, $metadataValue[static::KEY_TYPE]);
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
}
