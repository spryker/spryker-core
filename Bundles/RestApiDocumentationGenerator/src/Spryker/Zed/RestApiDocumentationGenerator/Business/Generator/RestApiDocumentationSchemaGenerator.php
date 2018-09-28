<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface;

class RestApiDocumentationSchemaGenerator implements RestApiDocumentationSchemaGeneratorInterface
{
    protected const KEY_ATTRIBUTES = 'attributes';
    protected const KEY_DATA = 'data';
    protected const KEY_ID = 'id';
    protected const KEY_LINKS = 'links';
    protected const KEY_RELATIONSHIPS = 'relationships';
    protected const KEY_REST_REQUEST_PARAMETER = 'rest_request_parameter';
    protected const KEY_SELF = 'self';
    protected const KEY_TYPE = 'type';

    protected const VALUE_BOOLEAN = 'boolean';
    protected const VALUE_INTEGER = 'integer';
    protected const VALUE_NUMBER = 'number';
    protected const VALUE_STRING = 'string';

    protected const DATA_TYPES_MAPPING_LIST = [
        'int' => self::VALUE_INTEGER,
        'bool' => self::VALUE_BOOLEAN,
        'float' => self::VALUE_NUMBER,
    ];

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
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface
     */
    protected $resourceRelationshipPluginAnalyzer;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected $resourceTransferAnalyzer;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface
     */
    protected $schemaRenderer;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface $schemaRenderer
     */
    public function __construct(
        ResourceRelationshipsPluginAnalyzerInterface $resourceRelationshipPluginAnalyzer,
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        SchemaRendererInterface $schemaRenderer
    ) {
        $this->resourceRelationshipPluginAnalyzer = $resourceRelationshipPluginAnalyzer;
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
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
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException
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
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException
     *
     * @return string
     */
    public function addResponseResourceSchemaForPlugin(ResourceRoutePluginInterface $plugin): string
    {
        $transferClassName = $plugin->getResourceAttributesClassName();
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
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException
     *
     * @return string
     */
    public function addResponseCollectionSchemaForPlugin(ResourceRoutePluginInterface $plugin): string
    {
        $transferClassName = $plugin->getResourceAttributesClassName();
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
        $schemaData = $this->createSchemaDataTransfer($attributesSchemaName);
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
        $schemaData = $this->createSchemaDataTransfer($attributesSchemaName);
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
        $schemaData = $this->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->createReferencePropertyTransfer(static::KEY_DATA, $ref));

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
        $schemaData = $this->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_STRING));
        $schemaData->addProperty($this->createReferencePropertyTransfer(static::KEY_ATTRIBUTES, $ref));

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
        $schemaData = $this->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->createReferencePropertyTransfer(static::KEY_DATA, $ref));
        $schemaData->addProperty($this->createReferencePropertyTransfer(static::KEY_LINKS, static::SCHEMA_NAME_LINKS));

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
        $schemaData = $this->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_STRING));
        $schemaData->addProperty($this->createTypePropertyTransfer(static::KEY_ID, static::VALUE_STRING));
        $schemaData->addProperty($this->createReferencePropertyTransfer(static::KEY_ATTRIBUTES, $ref));
        $schemaData->addProperty($this->createReferencePropertyTransfer(static::KEY_LINKS, static::SCHEMA_NAME_LINKS));

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
        $schemaData = $this->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->createArrayOfObjectsPropertyTransfer(static::KEY_DATA, $ref));

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
        $dataSchemaData = $this->createSchemaDataTransfer($dataSchemaName);
        $dataSchemaData->addProperty($this->createReferencePropertyTransfer(static::KEY_RELATIONSHIPS, $relationshipsSchemaName));
        $this->addSchemaData($dataSchemaData);

        $relationshipsSchemaData = $this->createSchemaDataTransfer($relationshipsSchemaName);
        foreach ($resourceRelationships as $resourceRelationship) {
            $relationshipsSchemaData->addProperty($this->createArrayOfObjectsPropertyTransfer($resourceRelationship, static::SCHEMA_NAME_RELATIONSHIPS));
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
        $linksSchema = $this->createSchemaDataTransfer(static::SCHEMA_NAME_LINKS);
        $linksSchema->addProperty($this->createTypePropertyTransfer(static::KEY_SELF, static::VALUE_STRING));

        $this->addSchemaData($linksSchema);
    }

    /**
     * @return void
     */
    protected function addDefaultRelationshipsSchema(): void
    {
        $relationshipsSchema = $this->createSchemaDataTransfer(static::SCHEMA_NAME_RELATIONSHIPS);
        $relationshipsSchema->addProperty($this->createTypePropertyTransfer(static::KEY_ID, static::VALUE_STRING));
        $relationshipsSchema->addProperty($this->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_STRING));

        $this->addSchemaData($relationshipsSchema);
    }

    /**
     * @param string $metadataKey
     * @param array $metadataValue
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    protected function createSchemaPropertyTransfer(string $metadataKey, array $metadataValue): RestApiDocumentationSchemaPropertyTransfer
    {
        if (class_exists($metadataValue[static::KEY_TYPE])) {
            return $this->createObjectSchemaTypeTransfer($metadataKey, $metadataValue);
        }

        return $this->createScalarSchemaTypeTransfer($metadataKey, $metadataValue[static::KEY_TYPE]);
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    protected function createScalarSchemaTypeTransfer(string $key, string $type): RestApiDocumentationSchemaPropertyTransfer
    {
        if (substr($type, -2) === '[]') {
            return $this->createArrayOfTypesPropertyTransfer($key, $this->mapScalarSchemaType(substr($type, 0, -2)));
        }

        return $this->createTypePropertyTransfer($key, $this->mapScalarSchemaType($type));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function mapScalarSchemaType(string $type): string
    {
        if (array_key_exists($type, static::DATA_TYPES_MAPPING_LIST)) {
            return static::DATA_TYPES_MAPPING_LIST[$type];
        }

        return $type;
    }

    /**
     * @param string $key
     * @param array $objectMetadata
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    protected function createObjectSchemaTypeTransfer(string $key, array $objectMetadata): RestApiDocumentationSchemaPropertyTransfer
    {
        $schemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($objectMetadata[static::KEY_TYPE]);
        $this->addResponseDataAttributesSchemaFromTransfer(new $objectMetadata[static::KEY_TYPE](), $schemaName);

        if ($objectMetadata['is_collection']) {
            return $this->createArrayOfObjectsPropertyTransfer($key, $schemaName);
        }

        return $this->createReferencePropertyTransfer($key, $schemaName);
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer
     */
    public function createSchemaDataTransfer(string $name): RestApiDocumentationSchemaDataTransfer
    {
        $schemaData = new RestApiDocumentationSchemaDataTransfer();
        $schemaData->setName($name);

        return $schemaData;
    }

    /**
     * @param string $name
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    public function createTypePropertyTransfer(string $name, string $type): RestApiDocumentationSchemaPropertyTransfer
    {
        $typeProperty = new RestApiDocumentationSchemaPropertyTransfer();
        $typeProperty->setName($name);
        $typeProperty->setType($type);

        return $typeProperty;
    }

    /**
     * @param string $name
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    public function createReferencePropertyTransfer(string $name, string $ref): RestApiDocumentationSchemaPropertyTransfer
    {
        $referenceProperty = new RestApiDocumentationSchemaPropertyTransfer();
        $referenceProperty->setName($name);
        $referenceProperty->setReference(sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref));

        return $referenceProperty;
    }

    /**
     * @param string $name
     * @param string $itemsRef
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    public function createArrayOfObjectsPropertyTransfer(string $name, string $itemsRef): RestApiDocumentationSchemaPropertyTransfer
    {
        $arrayProperty = new RestApiDocumentationSchemaPropertyTransfer();
        $arrayProperty->setName($name);
        $arrayProperty->setItemsReference(sprintf(static::PATTERN_SCHEMA_REFERENCE, $itemsRef));

        return $arrayProperty;
    }

    /**
     * @param string $name
     * @param string $itemsType
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    public function createArrayOfTypesPropertyTransfer(string $name, string $itemsType): RestApiDocumentationSchemaPropertyTransfer
    {
        $arrayProperty = new RestApiDocumentationSchemaPropertyTransfer();
        $arrayProperty->setName($name);
        $arrayProperty->setType($itemsType);

        return $arrayProperty;
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer $schemaData
     *
     * @return void
     */
    protected function addSchemaData(RestApiDocumentationSchemaDataTransfer $schemaData): void
    {
        $this->schemas = array_replace_recursive($this->schemas, $this->schemaRenderer->render($schemaData));
    }
}
