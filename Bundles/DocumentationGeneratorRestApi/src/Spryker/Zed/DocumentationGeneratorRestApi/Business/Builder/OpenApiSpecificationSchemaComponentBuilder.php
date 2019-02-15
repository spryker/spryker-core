<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder;

use Generated\Shared\Transfer\SchemaDataTransfer;
use Generated\Shared\Transfer\SchemaPropertyTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface;

class OpenApiSpecificationSchemaComponentBuilder implements SchemaComponentBuilderInterface
{
    protected const VALUE_TYPE_ARRAY = 'array';
    protected const VALUE_TYPE_BOOLEAN = 'boolean';
    protected const VALUE_TYPE_INTEGER = 'integer';
    protected const VALUE_TYPE_NUMBER = 'number';
    protected const VALUE_TYPE_STRING = 'string';

    protected const DATA_TYPES_MAPPING_LIST = [
        'int' => self::VALUE_TYPE_INTEGER,
        'bool' => self::VALUE_TYPE_BOOLEAN,
        'float' => self::VALUE_TYPE_NUMBER,
    ];

    protected const KEY_TYPE = 'type';
    protected const KEY_IS_COLLECTION = 'is_collection';
    protected const KEY_IS_NULLABLE = 'is_nullable';
    protected const PATTERN_SCHEMA_REFERENCE = '#/components/schemas/%s';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected $resourceTransferAnalyzer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     */
    public function __construct(ResourceTransferAnalyzerInterface $resourceTransferAnalyzer)
    {
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
    }

    /**
     * @param string $key
     * @param string $schemaName
     * @param array $objectMetadata
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createObjectSchemaTypeTransfer(string $key, string $schemaName, array $objectMetadata): SchemaPropertyTransfer
    {
        if ($objectMetadata[static::KEY_IS_COLLECTION]) {
            return $this->createArrayOfObjectsPropertyTransfer($key, $schemaName, $objectMetadata[static::KEY_IS_NULLABLE]);
        }

        return $this->createReferencePropertyTransfer($key, $schemaName, $objectMetadata[static::KEY_IS_NULLABLE]);
    }

    /**
     * @param string $key
     * @param string $type
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createScalarSchemaTypeTransfer(string $key, string $type, bool $isNullable = false): SchemaPropertyTransfer
    {
        if (substr($type, -2) === '[]') {
            return $this->createArrayOfTypesPropertyTransfer($key, $this->mapScalarSchemaType(substr($type, 0, -2)), $isNullable);
        }
        if ($type === static::VALUE_TYPE_ARRAY) {
            return $this->createArrayOfMixedTypesPropertyTransfer($key, $isNullable);
        }

        return $this->createTypePropertyTransfer($key, $this->mapScalarSchemaType($type), $isNullable);
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createSchemaDataTransfer(string $name): SchemaDataTransfer
    {
        $schemaData = new SchemaDataTransfer();
        $schemaData->setName($name);

        return $schemaData;
    }

    /**
     * @param string $name
     * @param string $type
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createTypePropertyTransfer(string $name, string $type, bool $isNullable = false): SchemaPropertyTransfer
    {
        $typeProperty = new SchemaPropertyTransfer();
        $typeProperty->setName($name);
        $typeProperty->setType($type);
        $typeProperty->setIsNullable($isNullable);

        return $typeProperty;
    }

    /**
     * @param string $name
     * @param string $ref
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createReferencePropertyTransfer(string $name, string $ref, bool $isNullable = false): SchemaPropertyTransfer
    {
        $referenceProperty = new SchemaPropertyTransfer();
        $referenceProperty->setName($name);
        $referenceProperty->setReference(sprintf(static::PATTERN_SCHEMA_REFERENCE, $ref));
        $referenceProperty->setIsNullable($isNullable);

        return $referenceProperty;
    }

    /**
     * @param string $name
     * @param string $itemsRef
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createArrayOfObjectsPropertyTransfer(string $name, string $itemsRef, bool $isNullable = false): SchemaPropertyTransfer
    {
        $arrayProperty = new SchemaPropertyTransfer();
        $arrayProperty->setName($name);
        $arrayProperty->setItemsReference(sprintf(static::PATTERN_SCHEMA_REFERENCE, $itemsRef));
        $arrayProperty->setIsNullable($isNullable);

        return $arrayProperty;
    }

    /**
     * @param string $name
     * @param string $itemsType
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createArrayOfTypesPropertyTransfer(string $name, string $itemsType, bool $isNullable = false): SchemaPropertyTransfer
    {
        $arrayProperty = new SchemaPropertyTransfer();
        $arrayProperty->setName($name);
        $arrayProperty->setType(static::VALUE_TYPE_ARRAY);
        $arrayProperty->setItemsType($itemsType);
        $arrayProperty->setIsNullable($isNullable);

        return $arrayProperty;
    }

    /**
     * @param string $name
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createArrayOfMixedTypesPropertyTransfer(string $name, bool $isNullable = false): SchemaPropertyTransfer
    {
        $arrayProperty = new SchemaPropertyTransfer();
        $arrayProperty->setName($name);
        $arrayProperty->setType(static::VALUE_TYPE_ARRAY);
        $arrayProperty->setIsNullable($isNullable);

        return $arrayProperty;
    }

    /**
     * @param string $metadataKey
     * @param array $metadataValue
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createRequestSchemaPropertyTransfer(string $metadataKey, array $metadataValue): SchemaPropertyTransfer
    {
        if (!class_exists($metadataValue[static::KEY_TYPE])) {
            return $this->createScalarSchemaTypeTransfer($metadataKey, $metadataValue[static::KEY_TYPE], $metadataValue[static::KEY_IS_NULLABLE]);
        }
        $schemaName = $this->resourceTransferAnalyzer->createRequestAttributesSchemaNameFromTransferClassName($metadataValue[static::KEY_TYPE]);

        return $this->createObjectSchemaTypeTransfer($metadataKey, $schemaName, $metadataValue);
    }

    /**
     * @param string $metadataKey
     * @param array $metadataValue
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createResponseSchemaPropertyTransfer(string $metadataKey, array $metadataValue): SchemaPropertyTransfer
    {
        if (!class_exists($metadataValue[static::KEY_TYPE])) {
            return $this->createScalarSchemaTypeTransfer($metadataKey, $metadataValue[static::KEY_TYPE], $metadataValue[static::KEY_IS_NULLABLE]);
        }
        $schemaName = $this->resourceTransferAnalyzer->createResponseAttributesSchemaNameFromTransferClassName($metadataValue[static::KEY_TYPE]);

        return $this->createObjectSchemaTypeTransfer($metadataKey, $schemaName, $metadataValue);
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function mapScalarSchemaType(string $type): string
    {
        return static::DATA_TYPES_MAPPING_LIST[$type] ?? $type;
    }
}
