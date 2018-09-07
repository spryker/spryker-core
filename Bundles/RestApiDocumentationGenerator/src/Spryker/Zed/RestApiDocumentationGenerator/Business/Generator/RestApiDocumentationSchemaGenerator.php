<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use ReflectionClass;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

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
    protected const KEY_TYPE = 'type';

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

    protected const TRANSFER_NAME_PARTIAL_ATTRIBUTE = 'Attribute';
    protected const TRANSFER_NAME_PARTIAL_TRANSFER = 'Transfer';

    protected const SCHEMA_NAME_PARTIAL_REQUEST = 'Request';
    protected const SCHEMA_NAME_PARTIAL_RESPONSE = 'Response';
    protected const SCHEMA_NAME_PARTIAL_DATA = 'Data';
    protected const SCHEMA_NAME_PARTIAL_ATTRIBUTE = 'Attribute';
    protected const SCHEMA_NAME_PARTIAL_RELATIONSHIPS = 'Relationships';

    protected const KEY_REST_REQUEST_PARAMETER = 'rest_request_parameter';
    protected const REST_REQUEST_BODY_PARAMETER_REQUIRED = 'required';
    protected const REST_REQUEST_BODY_PARAMETER_UNNEEDED = 'no';

    /**
     * @var array
     */
    protected $schemas = [];

    /**
     * @var string
     */
    protected $lastAddedRequestSchemaKey;

    /**
     * @var string
     */
    protected $lastAddedResponseSchemaKey;

    /**
     * @return array
     */
    public function getSchemas(): array
    {
        $this->addDefaultSchemas();
        ksort($this->schemas);

        return $this->schemas;
    }

    /**
     * @return string
     */
    public function getLastAddedRequestSchemaKey(): string
    {
        return $this->lastAddedResponseSchemaKey;
    }

    /**
     * @return string
     */
    public function getLastAddedResponseSchemaKey(): string
    {
        return $this->lastAddedResponseSchemaKey;
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
     * @param string $transferClassName
     * @param array $resourceRelationships
     *
     * @return void
     */
    public function addResponseSchemaFromTransferClassName(string $transferClassName, array $resourceRelationships = []): void
    {
        if (!$this->isTransferValid($transferClassName)) {
            return;
        }

        $transfer = new $transferClassName;
        $this->addResponseSchema($transferClassName, $resourceRelationships, $transfer);
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
     * @param string $transferClassName
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return void
     */
    protected function addRequestSchema(string $transferClassName, AbstractTransfer $transfer): void
    {
        $transferClassNamePartial = $this->getTransferClassNamePartial($transferClassName);

        $requestSchemaName = $this->createSchemaNameFromTransferClassName(
            $transferClassNamePartial,
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTE . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            'Request'
        );
        $requestDataSchemaName = $this->createSchemaNameFromTransferClassName($transferClassNamePartial, static::TRANSFER_NAME_PARTIAL_ATTRIBUTE . static::TRANSFER_NAME_PARTIAL_TRANSFER, 'RequestData');
        $requestAttributesSchemaName = $this->createSchemaNameFromTransferClassName($transferClassNamePartial, static::TRANSFER_NAME_PARTIAL_ATTRIBUTE . static::TRANSFER_NAME_PARTIAL_TRANSFER, 'RequestAttributes');

        $this->addBaseSchema($requestSchemaName, $requestDataSchemaName);
        $this->addRequestDataSchema($requestDataSchemaName, $requestAttributesSchemaName);
        $this->addRequestDataAttributesSchemaFromTransfer($transfer, $requestAttributesSchemaName);

        $this->lastAddedRequestSchemaKey = $requestSchemaName;
    }

    /**
     * @param string $transferClassName
     * @param array $resourceRelationships
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return void
     */
    protected function addResponseSchema(string $transferClassName, array $resourceRelationships, AbstractTransfer $transfer): void
    {
        $transferClassNamePartial = $this->getTransferClassNamePartial($transferClassName);

        $responseSchemaName = $this->createSchemaNameFromTransferClassName($transferClassNamePartial, static::TRANSFER_NAME_PARTIAL_ATTRIBUTE . static::TRANSFER_NAME_PARTIAL_TRANSFER, 'Request');
        $responseDataSchemaName = $this->createSchemaNameFromTransferClassName($transferClassNamePartial, static::TRANSFER_NAME_PARTIAL_ATTRIBUTE . static::TRANSFER_NAME_PARTIAL_TRANSFER, 'RequestData');
        $responseAttributesSchemaName = $this->createSchemaNameFromTransferClassName($transferClassNamePartial, 'Transfer', '');

        $this->addBaseSchema($responseSchemaName, $responseDataSchemaName);
        $this->addResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName);
        $this->addResponseDataAttributesSchemaFromTransfer($transfer, $responseAttributesSchemaName);

        if ($resourceRelationships) {
            $resourceRelationshipsSchemaName = $this->createSchemaNameFromTransferClassName($transferClassNamePartial, static::TRANSFER_NAME_PARTIAL_ATTRIBUTE . static::TRANSFER_NAME_PARTIAL_TRANSFER, 'Relationships');
            $this->addRelationshipsDataToResponseDataSchema($responseDataSchemaName, $resourceRelationshipsSchemaName, $resourceRelationships);
        }

        $this->lastAddedResponseSchemaKey = $responseSchemaName;
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
        $schemaName = $this->createSchemaNameFromTransferClassName(end($transferClassNameExploded), 'Transfer', '');
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
    public function addResponseWithMultipleDataSchema(string $schemaName, string $ref): void
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
    public function addBaseSchema(string $schemaName, string $ref): void
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
                    static::KEY_REF => sprintf(static::PATTERN_SCHEMA_REFERENCE, 'RestRelationships'),
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

        return end($transferClassNameExploded);
    }

    /**
     * TODO: improve this somehow
     *
     * @return void
     */
    protected function addDefaultSchemas(): void
    {
        $transferClassNameExploded = $this->getTransferClassNameExploded(RestErrorMessageTransfer::class);
        $transferClassNamePartial = end($transferClassNameExploded);

        $restErrorSchemaName = $this->createSchemaNameFromTransferClassName($transferClassNamePartial, 'Transfer', '');
        $this->addResponseDataAttributesSchemaFromTransfer(new RestErrorMessageTransfer(), $restErrorSchemaName);

        $this->schemas['RestLinks'] = [
            static::KEY_PROPERTIES => [
                'self' => [
                    static::KEY_TYPE => static::VALUE_STRING,
                ],
            ],
        ];

        $this->schemas['RestRelationships'] = [
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
