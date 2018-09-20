<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use ReflectionClass;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRenderer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidator;

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

    protected const SCHEMA_NAME_RELATIONSHIPS = 'RestRelationships';
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
    protected const TRANSFER_NAME_PARTIAL_ATTRIBUTES = 'Attributes';
    protected const TRANSFER_NAME_PARTIAL_TRANSFER = 'Transfer';

    protected const SCHEMA_NAME_LINKS = 'RestLinks';
    protected const SCHEMA_NAME_PARTIAL_ATTRIBUTES = 'Attributes';
    protected const SCHEMA_NAME_PARTIAL_COLLECTION = 'Collection';
    protected const SCHEMA_NAME_PARTIAL_DATA = 'Data';
    protected const SCHEMA_NAME_PARTIAL_RELATIONSHIPS = 'Relationships';
    protected const SCHEMA_NAME_PARTIAL_REQUEST = 'Request';
    protected const SCHEMA_NAME_PARTIAL_RESPONSE = 'Response';

    protected const REST_REQUEST_BODY_PARAMETER_REQUIRED = 'required';
    protected const REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED = 'no';

    protected const MESSAGE_INVALID_TRANSFER_CLASS = 'Invalid transfer class provided in plugin %s';

    /**
     * @var array
     */
    protected $schemas = [];

    /**
     * @var \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    protected $restErrorSchemaReference;

    /**
     * @var \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    protected $resourceRelationshipCollectionPlugins;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SchemaRendererInterface
     */
    protected $schemaRenderer;

    /**
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[] $resourceRelationshipCollectionPlugins
     */
    public function __construct(array $resourceRelationshipCollectionPlugins)
    {
        $this->resourceRelationshipCollectionPlugins = $resourceRelationshipCollectionPlugins;
        $this->schemaRenderer = new SchemaRenderer(new ComponentValidator());
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
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    public function getRestErrorSchemaData(): RestApiDocumentationPathSchemaDataTransfer
    {
        return $this->restErrorSchemaReference;
    }

    /**
     * @param string $transferClassName
     *
     * @return void
     */
    public function addRequestSchemaFromTransferClassName(string $transferClassName): void
    {
        if (!$this->isTransferValid($transferClassName)) {
            return;
        }

        $transfer = new $transferClassName;
        $this->addRequestSchema($transferClassName, $transfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    public function addRequestSchemaForPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathSchemaDataTransfer
    {
        $transferClassName = $plugin->getResourceAttributesClassName();
        if (!$this->isTransferValid($transferClassName)) {
            throw new InvalidTransferClassException(sprintf(static::MESSAGE_INVALID_TRANSFER_CLASS, get_class($plugin)));
        }

        $transfer = new $transferClassName;
        return $this->addRequestSchema($transferClassName, $transfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    public function addResponseResourceSchemaForPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathSchemaDataTransfer
    {
        $transferClassName = $plugin->getResourceAttributesClassName();
        if (!$this->isTransferValid($transferClassName)) {
            throw new InvalidTransferClassException(sprintf(static::MESSAGE_INVALID_TRANSFER_CLASS, get_class($plugin)));
        }

        $resourceRelationships = $this->getResourceRelationshipsForPlugin($plugin);
        $transfer = new $transferClassName;

        return $this->addResponseSchema($transferClassName, $resourceRelationships, $transfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    public function addResponseCollectionSchemaForPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathSchemaDataTransfer
    {
        $transferClassName = $plugin->getResourceAttributesClassName();
        if (!$this->isTransferValid($transferClassName)) {
            throw new InvalidTransferClassException(sprintf(static::MESSAGE_INVALID_TRANSFER_CLASS, get_class($plugin)));
        }

        $resourceRelationships = $this->getResourceRelationshipsForPlugin($plugin);
        $transfer = new $transferClassName;

        return $this->addResponseSchema($transferClassName, $resourceRelationships, $transfer);
    }

    /**
     * @param string $transferClassName
     *
     * @return bool
     */
    protected function isTransferValid(string $transferClassName): bool
    {
        return class_exists($transferClassName) && is_subclass_of($transferClassName, AbstractTransfer::class);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return array
     */
    protected function getResourceRelationshipsForPlugin(ResourceRoutePluginInterface $plugin): array
    {
        $resourceRelationships = [];
        foreach ($this->resourceRelationshipCollectionPlugins as $resourceRelationshipCollectionPlugin) {
            $resourceRouteCollection = $resourceRelationshipCollectionPlugin->getResourceRelationshipCollection();
            if (!$resourceRouteCollection->hasRelationships($plugin->getResourceType())) {
                continue;
            }
            $relationshipPlugins = $resourceRouteCollection->getRelationships($plugin->getResourceType());
            foreach ($relationshipPlugins as $relationshipPlugin) {
                $resourceRelationships[] = $relationshipPlugin->getRelationshipResourceType();
            }
        }

        return $resourceRelationships;
    }

    /**
     * @param string $transferClassName
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    protected function addRequestSchema(string $transferClassName, AbstractTransfer $transfer): RestApiDocumentationPathSchemaDataTransfer
    {
        $transferClassNamePartial = $this->getTransferClassNamePartial($transferClassName);

        $requestSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST
        );
        $requestDataSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST . static::SCHEMA_NAME_PARTIAL_DATA
        );
        $requestAttributesSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST . static::SCHEMA_NAME_PARTIAL_ATTRIBUTES
        );

        $this->addRequestBaseSchema($requestSchemaName, $requestDataSchemaName);
        $this->addRequestDataSchema($requestDataSchemaName, $requestAttributesSchemaName);
        $this->addRequestDataAttributesSchemaFromTransfer($transfer, $requestAttributesSchemaName);

        return $this->createPathSchemaDataTransfer($requestSchemaName);
    }

    /**
     * @param string $transferClassName
     * @param array $resourceRelationships
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    protected function addResponseSchema(string $transferClassName, array $resourceRelationships, AbstractTransfer $transfer): RestApiDocumentationPathSchemaDataTransfer
    {
        $transferClassNamePartial = $this->getTransferClassNamePartial($transferClassName);

        $responseSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RESPONSE
        );
        $responseDataSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RESPONSE . static::SCHEMA_NAME_PARTIAL_DATA
        );
        $responseAttributesSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_TRANSFER,
            ''
        );

        $this->addResponseSchemas($responseSchemaName, $responseDataSchemaName, $responseAttributesSchemaName, $transfer);
        if ($resourceRelationships) {
            $this->addRelationshipSchemas($responseDataSchemaName, $resourceRelationships, $transferClassNamePartial);
        }

        return $this->createPathSchemaDataTransfer($responseSchemaName);
    }

    /**
     * @param string $transferClassName
     * @param array $resourceRelationships
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    protected function addCollectionResponseSchema(string $transferClassName, array $resourceRelationships, AbstractTransfer $transfer): RestApiDocumentationPathSchemaDataTransfer
    {
        $transferClassNamePartial = $this->getTransferClassNamePartial($transferClassName);

        $responseSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_COLLECTION . static::SCHEMA_NAME_PARTIAL_RESPONSE
        );
        $responseDataSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_COLLECTION . static::SCHEMA_NAME_PARTIAL_RESPONSE . static::SCHEMA_NAME_PARTIAL_DATA
        );
        $responseAttributesSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_TRANSFER,
            ''
        );

        $this->addResponseSchemas($responseSchemaName, $responseDataSchemaName, $responseAttributesSchemaName, $transfer);
        if ($resourceRelationships) {
            $this->addRelationshipSchemas($responseDataSchemaName, $resourceRelationships, $transferClassNamePartial);
        }

        return $this->createPathSchemaDataTransfer($responseSchemaName);
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
     * @param string $responseDataSchemaName
     * @param array $resourceRelationships
     * @param string $transferClassNamePartial
     *
     * @return void
     */
    protected function addRelationshipSchemas(string $responseDataSchemaName, array $resourceRelationships, string $transferClassNamePartial): void
    {
        $resourceRelationshipsSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RELATIONSHIPS
        );
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

        $transferMetadataValue = $this->getTransferMetadata($transfer);

        $schemaData = $this->createSchemaData($attributesSchemaName);
        foreach ($transferMetadataValue as $key => $value) {
            $schemaData->addProperty($this->addSchemaProperty($key, $value));
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

        $transferMetadataValue = $this->getTransferMetadata($transfer);

        $schemaData = $this->createSchemaData($attributesSchemaName);
        foreach ($transferMetadataValue as $key => $value) {
            if ($value[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED) {
                continue;
            }
            if ($value[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_REQUIRED) {
                $schemaData->addRequired($key);
            }
            $schemaData->addProperty($this->addSchemaProperty($key, $value));
        }

        $this->addSchemaData($schemaData);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return array
     */
    protected function getTransferMetadata(AbstractTransfer $transfer): array
    {
        $transferReflection = new ReflectionClass($transfer);
        $transferMetadata = $transferReflection->getProperty('transferMetadata');
        $transferMetadata->setAccessible(true);

        return $transferMetadata->getValue($transfer);
    }

    /**
     * @param string $metadataKey
     * @param array $metadataValue
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    protected function addSchemaProperty(string $metadataKey, array $metadataValue): RestApiDocumentationSchemaPropertyTransfer
    {
        if (class_exists($metadataValue[static::KEY_TYPE])) {
            return $this->formatObjectSchemaType($metadataKey, $metadataValue);
        }

        return $this->formatBasicSchemaType($metadataKey, $metadataValue[static::KEY_TYPE]);
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    protected function formatBasicSchemaType(string $key, string $type): RestApiDocumentationSchemaPropertyTransfer
    {
        if (substr($type, -2) === '[]') {
            return $this->createArrayOfTypesProperty($key, $this->mapBasicSchemaType(substr($type, 0, -2)));
        }

        return $this->createTypeProperty($key, $this->mapBasicSchemaType($type));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function mapBasicSchemaType(string $type): string
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
    protected function formatObjectSchemaType(string $key, array $objectMetadata): RestApiDocumentationSchemaPropertyTransfer
    {
        if ($objectMetadata['is_collection']) {
            return $this->createArrayOfObjectsProperty($key, $this->formatTransferClassToSchemaType($objectMetadata[static::KEY_TYPE]));
        }

        return $this->createReferenceProperty($key, $this->formatTransferClassToSchemaType($objectMetadata[static::KEY_TYPE]));
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function formatTransferClassToSchemaType(string $transferClassName): string
    {
        $transferClassNameExploded = $this->getTransferClassNameExploded($transferClassName);
        $schemaName = $this->createSchemaNameFromTransferClassName(
            array_slice($transferClassNameExploded, -1)[0],
            static::TRANSFER_NAME_PARTIAL_TRANSFER,
            ''
        );
        $this->addResponseDataAttributesSchemaFromTransfer(new $transferClassName, $schemaName);

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $schemaName);
    }

    /**
     * @param string $transferClassName
     * @param string $removal
     * @param string $addition
     *
     * @return string
     */
    protected function createSchemaNameFromTransferClassName(string $transferClassName, string $removal, string $addition): string
    {
        return str_replace($removal, $addition, $transferClassName);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addRequestBaseSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->createSchemaData($schemaName);
        $schemaData->addProperty($this->createReferenceProperty(static::KEY_DATA, sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref)));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addRequestDataSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->createSchemaData($schemaName);
        $schemaData->addProperty($this->createTypeProperty(static::KEY_TYPE, static::VALUE_STRING));
        $schemaData->addProperty($this->createReferenceProperty(static::KEY_ATTRIBUTES, sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref)));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addResponseBaseSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->createSchemaData($schemaName);
        $schemaData->addProperty($this->createReferenceProperty(static::KEY_DATA, sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref)));
        $schemaData->addProperty($this->createReferenceProperty(static::KEY_LINKS, sprintf(static::PATTERN_SCHEMA_REFERENCE, static::SCHEMA_NAME_LINKS)));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addResponseDataSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->createSchemaData($schemaName);
        $schemaData->addProperty($this->createTypeProperty(static::KEY_TYPE, static::VALUE_STRING));
        $schemaData->addProperty($this->createTypeProperty(static::KEY_ID, static::VALUE_STRING));
        $schemaData->addProperty($this->createReferenceProperty(static::KEY_ATTRIBUTES, sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref)));
        $schemaData->addProperty($this->createReferenceProperty(static::KEY_LINKS, sprintf(static::PATTERN_SCHEMA_REFERENCE, static::SCHEMA_NAME_LINKS)));

        $this->addSchemaData($schemaData);
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addCollectionBaseSchema(string $schemaName, string $ref): void
    {
        $schemaData = $this->createSchemaData($schemaName);
        $schemaData->addProperty($this->createArrayOfObjectsProperty(static::KEY_DATA, sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref)));

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
        $dataSchemaData = $this->createSchemaData($dataSchemaName);
        $dataSchemaData->addProperty($this->createReferenceProperty(static::KEY_RELATIONSHIPS, sprintf(static::PATTERN_SCHEMA_REFERENCE, $relationshipsSchemaName)));
        $this->addSchemaData($dataSchemaData);

        $relationshipsSchemaData = $this->createSchemaData($relationshipsSchemaName);
        foreach ($resourceRelationships as $resourceRelationship) {
            $relationshipsSchemaData->addProperty($this->createArrayOfObjectsProperty($resourceRelationship, sprintf(static::PATTERN_SCHEMA_REFERENCE, static::SCHEMA_NAME_RELATIONSHIPS)));
        }
        $this->addSchemaData($relationshipsSchemaData);
    }

    /**
     * @param string $transferClassName
     *
     * @return array
     */
    protected function getTransferClassNameExploded(string $transferClassName): array
    {
        return explode('\\', $transferClassName);
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getTransferClassNamePartial(string $transferClassName): string
    {
        $transferClassNameExploded = $this->getTransferClassNameExploded($transferClassName);

        return array_slice($transferClassNameExploded, -1)[0];
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
        $transferClassNamePartial = $this->getTransferClassNamePartial(RestErrorMessageTransfer::class);
        $restErrorSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_TRANSFER,
            ''
        );
        $this->addResponseDataAttributesSchemaFromTransfer(new RestErrorMessageTransfer(), $restErrorSchemaName);
        $this->restErrorSchemaReference = $this->createPathSchemaDataTransfer($restErrorSchemaName);
    }

    /**
     * @return void
     */
    protected function addDefaultLinksSchema(): void
    {
        $linksSchema = $this->createSchemaData(static::SCHEMA_NAME_LINKS);
        $linksSchema->addProperty($this->createTypeProperty(static::KEY_SELF, static::VALUE_STRING));

        $this->addSchemaData($linksSchema);
    }

    /**
     * @return void
     */
    protected function addDefaultRelationshipsSchema(): void
    {
        $relationshipsSchema = $this->createSchemaData(static::SCHEMA_NAME_RELATIONSHIPS);
        $relationshipsSchema->addProperty($this->createTypeProperty(static::KEY_ID, static::VALUE_STRING));
        $relationshipsSchema->addProperty($this->createTypeProperty(static::KEY_TYPE, static::VALUE_STRING));

        $this->addSchemaData($relationshipsSchema);
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer
     */
    protected function createSchemaData(string $name): RestApiDocumentationSchemaDataTransfer
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
    protected function createTypeProperty(string $name, string $type): RestApiDocumentationSchemaPropertyTransfer
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
    protected function createReferenceProperty(string $name, string $ref): RestApiDocumentationSchemaPropertyTransfer
    {
        $referenceProperty = new RestApiDocumentationSchemaPropertyTransfer();
        $referenceProperty->setName($name);
        $referenceProperty->setReference($ref);

        return $referenceProperty;
    }

    /**
     * @param string $name
     * @param string $itemsRef
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    protected function createArrayOfObjectsProperty(string $name, string $itemsRef): RestApiDocumentationSchemaPropertyTransfer
    {
        $arrayProperty = new RestApiDocumentationSchemaPropertyTransfer();
        $arrayProperty->setName($name);
        $arrayProperty->setItemsReference($itemsRef);

        return $arrayProperty;
    }

    /**
     * @param string $name
     * @param string $itemsType
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer
     */
    protected function createArrayOfTypesProperty(string $name, string $itemsType): RestApiDocumentationSchemaPropertyTransfer
    {
        $arrayProperty = new RestApiDocumentationSchemaPropertyTransfer();
        $arrayProperty->setName($name);
        $arrayProperty->setType($itemsType);

        return $arrayProperty;
    }

    /**
     * @param string $schemaRef
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer
     */
    protected function createPathSchemaDataTransfer(string $schemaRef): RestApiDocumentationPathSchemaDataTransfer
    {
        $schemaDataTransfer = new RestApiDocumentationPathSchemaDataTransfer();
        $schemaDataTransfer->setSchemaReference(sprintf(static::PATTERN_SCHEMA_REFERENCE, $schemaRef));

        return $schemaDataTransfer;
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
