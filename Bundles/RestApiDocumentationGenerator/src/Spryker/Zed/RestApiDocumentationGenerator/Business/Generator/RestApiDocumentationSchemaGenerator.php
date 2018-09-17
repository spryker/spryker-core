<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use ReflectionClass;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidTransferClassException;

class RestApiDocumentationSchemaGenerator implements RestApiDocumentationSchemaGeneratorInterface
{
    protected const KEY_ATTRIBUTES = 'attributes';
    protected const KEY_DATA = 'data';
    protected const KEY_ID = 'id';
    protected const KEY_ITEMS = 'items';
    protected const KEY_LINKS = 'links';
    protected const KEY_PROPERTIES = 'properties';
    protected const KEY_REF = '$ref';
    protected const KEY_RELATIONSHIPS = 'relationships';
    protected const KEY_SELF = 'self';
    protected const KEY_TYPE = 'type';

    protected const SCHEMA_NAME_LINKS = 'RestLinks';
    protected const SCHEMA_NAME_RELATIONSHIPS = 'RestRelationships';

    protected const VALUE_ARRAY = 'array';
    protected const VALUE_BOOLEAN = 'boolean';
    protected const VALUE_INTEGER = 'integer';
    protected const VALUE_NUMBER = 'number';
    protected const VALUE_OBJECT = 'object';
    protected const VALUE_STRING = 'string';

    protected const DATA_TYPES_MAPPING_LIST = [
        'int' => self::VALUE_INTEGER,
        'bool' => self::VALUE_BOOLEAN,
        'float' => self::VALUE_NUMBER,
    ];

    protected const PATTERN_SCHEMA_REFERENCE = '#/components/schemas/%s';

    protected const TRANSFER_NAME_PARTIAL_ATTRIBUTES = 'Attributes';
    protected const TRANSFER_NAME_PARTIAL_TRANSFER = 'Transfer';

    protected const SCHEMA_NAME_PARTIAL_ATTRIBUTES = 'Attributes';
    protected const SCHEMA_NAME_PARTIAL_COLLECTION = 'Collection';
    protected const SCHEMA_NAME_PARTIAL_DATA = 'Data';
    protected const SCHEMA_NAME_PARTIAL_RELATIONSHIPS = 'Relationships';
    protected const SCHEMA_NAME_PARTIAL_REQUEST = 'Request';
    protected const SCHEMA_NAME_PARTIAL_RESPONSE = 'Response';

    protected const KEY_REST_REQUEST_PARAMETER = 'rest_request_parameter';
    protected const REST_REQUEST_BODY_PARAMETER_REQUIRED = 'required';
    protected const REST_REQUEST_BODY_PARAMETER_UNNEEDED = 'no';

    protected const MESSAGE_INVALID_TRANSFER_CLASS = 'Invalid transfer class provided in plugin %s';

    /**
     * @var array
     */
    protected $schemas = [];

    /**
     * @var string
     */
    protected $restErrorSchemaName;

    /**
     * @var \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    protected $resourceRelationshipCollectionPlugins;

    /**
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[] $resourceRelationshipCollectionPlugins
     */
    public function __construct(array $resourceRelationshipCollectionPlugins)
    {
        $this->resourceRelationshipCollectionPlugins = $resourceRelationshipCollectionPlugins;
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
    public function getRestErrorSchemaName(): string
    {
        return $this->restErrorSchemaName;
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
     * @return string
     */
    public function addRequestSchemaForPlugin(ResourceRoutePluginInterface $plugin): string
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
     * @return string
     */
    public function addResponseResourceSchemaForPlugin(ResourceRoutePluginInterface $plugin): string
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
     * @return string
     */
    public function addResponseCollectionSchemaForPlugin(ResourceRoutePluginInterface $plugin): string
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
            if ($resourceRouteCollection->hasRelationships($plugin->getResourceType())) {
                $relationshipPlugins = $resourceRouteCollection->getRelationships($plugin->getResourceType());
                foreach ($relationshipPlugins as $relationshipPlugin) {
                    $resourceRelationships[] = $relationshipPlugin->getRelationshipResourceType();
                }
            }
        }

        return $resourceRelationships;
    }

    /**
     * @param string $transferClassName
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return string
     */
    protected function addRequestSchema(string $transferClassName, AbstractTransfer $transfer): string
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

        return $requestSchemaName;
    }

    /**
     * @param string $transferClassName
     * @param array $resourceRelationships
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return string
     */
    protected function addResponseSchema(string $transferClassName, array $resourceRelationships, AbstractTransfer $transfer): string
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

        return $responseSchemaName;
    }

    /**
     * @param string $transferClassName
     * @param array $resourceRelationships
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return string
     */
    protected function addCollectionResponseSchema(string $transferClassName, array $resourceRelationships, AbstractTransfer $transfer): string
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

        return $responseSchemaName;
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

        $schemaProperties = [];
        foreach ($transferMetadataValue as $key => $value) {
            $schemaProperties = $this->addSchemaProperty($schemaProperties, $key, $value);
        }

        $this->schemas[$attributesSchemaName][static::KEY_PROPERTIES] = $schemaProperties;
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

        $schemaProperties = [];
        $required = [];
        foreach ($transferMetadataValue as $key => $value) {
            if ($value[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_UNNEEDED) {
                continue;
            }
            if ($value[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_REQUIRED) {
                $required[] = $key;
            }
            $schemaProperties = $this->addSchemaProperty($schemaProperties, $key, $value);
        }

        if ($required) {
            $this->schemas[$attributesSchemaName][static::REST_REQUEST_BODY_PARAMETER_REQUIRED] = $required;
        }
        $this->schemas[$attributesSchemaName][static::KEY_PROPERTIES] = $schemaProperties;
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
     * @param array $schemaProperties
     * @param string $metadataKey
     * @param array $metadataValue
     *
     * @return array
     */
    protected function addSchemaProperty(array $schemaProperties, string $metadataKey, array $metadataValue): array
    {
        if (class_exists($metadataValue[static::KEY_TYPE])) {
            $schemaProperties += $this->formatObjectSchemaType($metadataKey, $metadataValue);
        } else {
            $schemaProperties += $this->formatBasicSchemaType($metadataKey, $metadataValue[static::KEY_TYPE]);
        }
        return $schemaProperties;
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return array
     */
    protected function formatBasicSchemaType(string $key, string $type): array
    {
        if (substr($type, -2) === '[]') {
            $schemaProperties[$key] = [
                static::KEY_TYPE => static::VALUE_ARRAY,
                static::KEY_ITEMS => [
                    static::KEY_TYPE => $this->mapBasicSchemaType(substr($type, 0, -2)),
                ],
            ];

            return $schemaProperties;
        }

        $schemaProperties[$key][static::KEY_TYPE] = $this->mapBasicSchemaType($type);

        return $schemaProperties;
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
     * @return array
     */
    protected function formatObjectSchemaType(string $key, array $objectMetadata): array
    {
        if ($objectMetadata['is_collection']) {
            $schemaProperties[$key][static::KEY_TYPE] = static::VALUE_ARRAY;
            $schemaProperties[$key][static::KEY_ITEMS][static::KEY_REF] = $this->formatTransferClassToSchemaType($objectMetadata[static::KEY_TYPE]);

            return $schemaProperties;
        }

        $schemaProperties[$key][static::KEY_REF] = $this->formatTransferClassToSchemaType($objectMetadata[static::KEY_TYPE]);

        return $schemaProperties;
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
    public function addCollectionBaseSchema(string $schemaName, string $ref): void
    {
        $this->schemas[$schemaName] = [
            static::KEY_PROPERTIES => [
                static::KEY_DATA => [
                    static::KEY_TYPE => static::VALUE_ARRAY,
                    static::KEY_ITEMS => [
                        static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addRequestBaseSchema(string $schemaName, string $ref): void
    {
        $this->schemas[$schemaName] = [
            static::KEY_PROPERTIES => [
                static::KEY_DATA => [
                    static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref),
                ],
            ],
        ];
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addResponseBaseSchema(string $schemaName, string $ref): void
    {
        $this->schemas[$schemaName] = [
            static::KEY_PROPERTIES => [
                static::KEY_DATA => [
                    static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref),
                ],
                static::KEY_LINKS => [
                    static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, static::SCHEMA_NAME_LINKS),
                ],
            ],
        ];
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addRequestDataSchema(string $schemaName, string $ref): void
    {
        $this->schemas[$schemaName] = [
            static::KEY_PROPERTIES => [
                static::KEY_TYPE => [
                    static::KEY_TYPE => static::VALUE_STRING,
                ],
                static::KEY_ATTRIBUTES => [
                    static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref),
                ],
            ],
        ];
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addResponseDataSchema(string $schemaName, string $ref): void
    {
        $this->schemas[$schemaName] = [
            static::KEY_PROPERTIES => [
                static::KEY_TYPE => [
                    static::KEY_TYPE => static::VALUE_STRING,
                ],
                static::KEY_ID => [
                    static::KEY_TYPE => static::VALUE_STRING,
                ],
                static::KEY_ATTRIBUTES => [
                    static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref),
                ],
                static::KEY_LINKS => [
                    static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, 'RestLinks'),
                ],
            ],
        ];
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
        $this->schemas[$dataSchemaName][static::KEY_PROPERTIES][static::KEY_RELATIONSHIPS] = [
            static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, $relationshipsSchemaName),
        ];

        $properties = [];
        foreach ($resourceRelationships as $resourceRelationship) {
            $properties[$resourceRelationship] = [
                static::KEY_TYPE => static::VALUE_ARRAY,
                static::KEY_ITEMS => [
                    static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, static::SCHEMA_NAME_RELATIONSHIPS),
                ],
            ];
        }

        $this->schemas[$relationshipsSchemaName] = [
            static::KEY_PROPERTIES => $properties,
        ];
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
        $this->restErrorSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_TRANSFER,
            ''
        );
        $this->addResponseDataAttributesSchemaFromTransfer(new RestErrorMessageTransfer(), $this->restErrorSchemaName);
    }

    /**
     * @return void
     */
    protected function addDefaultLinksSchema(): void
    {
        $this->schemas[static::SCHEMA_NAME_LINKS] = [
            static::KEY_PROPERTIES => [
                static::KEY_SELF => [
                    static::KEY_TYPE => static::VALUE_STRING,
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    protected function addDefaultRelationshipsSchema(): void
    {
        $this->schemas[static::SCHEMA_NAME_RELATIONSHIPS] = [
            static::KEY_PROPERTIES => [
                static::KEY_ID => [
                    static::KEY_TYPE => static::VALUE_STRING,
                ],
                static::KEY_TYPE => [
                    static::KEY_TYPE => static::VALUE_STRING,
                ],
            ],
        ];
    }
}
