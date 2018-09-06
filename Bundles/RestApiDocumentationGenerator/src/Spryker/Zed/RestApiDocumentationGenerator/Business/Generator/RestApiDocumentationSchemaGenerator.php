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

    /**
     * @var array
     */
    protected $schemas = [];

    /**
     * @var string
     */
    protected $lastAddedResponseSchema;

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
     * TODO: improve this somehow
     *
     * @return void
     */
    protected function addDefaultSchemas(): void
    {
        $restErrorSchemaName = $this->getResponseDataAttributesSchemaNameFromTransferClassName(RestErrorMessageTransfer::class);
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

    /**
     * @param string $transferClassName
     * @param array $resourceRelationships
     *
     * @return void
     */
    public function addSchemaFromTransferClassName(string $transferClassName, array $resourceRelationships = []): void
    {
        if (!class_exists($transferClassName)) {
            return;
        }

        $transfer = new $transferClassName;
        if (!$transfer instanceof AbstractTransfer) {
            return;
        }

        $responseSchemaName = $this->getResponseSchemaNameFromTransferClassName($transferClassName);
        $responseDataSchemaName = $this->getResponseDataSchemaNameFromTransferClassName($transferClassName);
        $responseAttributesSchemaName = $this->getResponseDataAttributesSchemaNameFromTransferClassName($transferClassName);

        $this->addResponseSchema($responseSchemaName, $responseDataSchemaName);
        $this->addResponseDataSchema($responseDataSchemaName, $responseAttributesSchemaName);
        $this->addResponseDataAttributesSchemaFromTransfer($transfer, $responseAttributesSchemaName);

        if ($resourceRelationships) {
            $resourceRelationshipsSchemaName = $this->getResponseDataRelationshipsSchemaNameFromTransferClass($transferClassName);
            $this->addRelationshipsDataToResponseDataSchema($responseDataSchemaName, $resourceRelationshipsSchemaName, $resourceRelationships);
        }

        $this->lastAddedResponseSchema = $responseSchemaName;
    }

    /**
     * @return string
     */
    public function getLastAddedSchemaKey(): string
    {
        return $this->lastAddedResponseSchema;
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

        //to avoid infinite loop
        $this->schemas[$attributesSchemaName][static::KEY_PROPERTIES] = [];
        $transferReflection = new ReflectionClass($transfer);
        $transferMetadata = $transferReflection->getProperty('transferMetadata');
        $transferMetadata->setAccessible(true);
        $transferMetadataValue = $transferMetadata->getValue($transfer);

        $schemaProperties = [];
        foreach ($transferMetadataValue as $key => $value) {
            if (class_exists($value[static::KEY_TYPE])) {
                $schemaProperties += $this->formatObjectSchemaType($key, $value);
            } else {
                $schemaProperties += $this->formatBasicSchemaType($key, $value[static::KEY_TYPE]);
            }
        }

        $this->schemas[$attributesSchemaName][static::KEY_PROPERTIES] = $schemaProperties;
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
        $this->addResponseDataAttributesSchemaFromTransfer(new $transferClassName, $this->getResponseDataAttributesSchemaNameFromTransferClassName($transferClassName));

        return sprintf(static::PATTERN_SCHEMA_REFERENCE, $this->getResponseDataAttributesSchemaNameFromTransferClassName($transferClassName));
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getResponseSchemaNameFromTransferClassName(string $transferClassName): string
    {
        $transferClassNameExploded = explode('\\', $transferClassName);

        return str_replace('AttributesTransfer', 'Response', end($transferClassNameExploded));
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getResponseDataSchemaNameFromTransferClassName(string $transferClassName): string
    {
        $transferClassNameExploded = explode('\\', $transferClassName);

        return str_replace('AttributesTransfer', 'ResponseData', end($transferClassNameExploded));
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getResponseDataAttributesSchemaNameFromTransferClassName(string $transferClassName): string
    {
        $transferClassNameExploded = explode('\\', $transferClassName);

        return str_replace('Transfer', '', end($transferClassNameExploded));
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getResponseDataRelationshipsSchemaNameFromTransferClass(string $transferClassName): string
    {
        $transferClassNameExploded = explode('\\', $transferClassName);

        return str_replace('AttributesTransfer', 'Relationships', end($transferClassNameExploded));
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
    public function addResponseSchema(string $schemaName, string $ref): void
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
    public function addRequestDataSchemaWithId(string $schemaName, string $ref): void
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
            ],
        ];
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return void
     */
    public function addRequestDataSchemaWithoutId(string $schemaName, string $ref): void
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
}
