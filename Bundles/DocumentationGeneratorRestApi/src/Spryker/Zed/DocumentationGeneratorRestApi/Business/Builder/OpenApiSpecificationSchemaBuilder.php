<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder;

use Generated\Shared\Transfer\SchemaDataTransfer;

class OpenApiSpecificationSchemaBuilder implements SchemaBuilderInterface
{
    protected const KEY_ATTRIBUTES = 'attributes';
    protected const KEY_DATA = 'data';
    protected const KEY_ID = 'id';
    protected const KEY_LINKS = 'links';
    protected const KEY_RELATIONSHIPS = 'relationships';
    protected const KEY_REST_REQUEST_PARAMETER = 'rest_request_parameter';
    protected const KEY_IS_NULLABLE = 'is_nullable';
    protected const KEY_SELF = 'self';
    protected const KEY_TYPE = 'type';

    protected const VALUE_TYPE_STRING = 'string';
    protected const SCHEMA_NAME_LINKS = 'RestLinks';
    protected const SCHEMA_NAME_RELATIONSHIPS = 'RestRelationships';

    protected const REST_REQUEST_BODY_PARAMETER_REQUIRED = 'required';
    protected const REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED = 'no';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface
     */
    protected $schemaComponentBuilder;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder\SchemaComponentBuilderInterface $schemaComponentBuilder
     */
    public function __construct(SchemaComponentBuilderInterface $schemaComponentBuilder)
    {
        $this->schemaComponentBuilder = $schemaComponentBuilder;
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRequestBaseSchema(string $schemaName, string $ref): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_DATA, $ref));

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRequestDataSchema(string $schemaName, string $ref): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_TYPE_STRING));
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_ATTRIBUTES, $ref));

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param array $transferMetadata
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRequestDataAttributesSchema(string $schemaName, array $transferMetadata): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        foreach ($transferMetadata as $key => $value) {
            if ($value[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED) {
                continue;
            }
            if ($value[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_REQUIRED) {
                $schemaData->addRequired($key);
            }
            $schemaData->addProperty($this->schemaComponentBuilder->createRequestSchemaPropertyTransfer($key, $value));
        }

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createResponseBaseSchema(string $schemaName, string $ref): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_DATA, $ref));
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_LINKS, static::SCHEMA_NAME_LINKS));

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param string $ref
     * @param bool $isIdNullable
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createResponseDataSchema(string $schemaName, string $ref, bool $isIdNullable = false): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_TYPE_STRING));
        $schemaData->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_ID, static::VALUE_TYPE_STRING, $isIdNullable));
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_ATTRIBUTES, $ref));
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_LINKS, static::SCHEMA_NAME_LINKS));

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param array $transferMetadata
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createResponseDataAttributesSchema(string $schemaName, array $transferMetadata): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        foreach ($transferMetadata as $key => $value) {
            $schemaData->addProperty($this->schemaComponentBuilder->createResponseSchemaPropertyTransfer($key, $value));
        }

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createCollectionResponseBaseSchema(string $schemaName, string $ref): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createArrayOfObjectsPropertyTransfer(static::KEY_DATA, $ref));

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRelationshipBaseSchema(string $schemaName, string $ref): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_RELATIONSHIPS, $ref));

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param array $resourceRelationships
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRelationshipDataSchema(string $schemaName, array $resourceRelationships): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        foreach ($resourceRelationships as $resourceRelationship) {
            $schemaData->addProperty($this->schemaComponentBuilder->createArrayOfObjectsPropertyTransfer($resourceRelationship, static::SCHEMA_NAME_RELATIONSHIPS));
        }

        return $schemaData;
    }

    /**
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createDefaultRelationshipDataAttributesSchema(): SchemaDataTransfer
    {
        $relationshipsSchema = $this->schemaComponentBuilder->createSchemaDataTransfer(static::SCHEMA_NAME_RELATIONSHIPS);
        $relationshipsSchema->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_ID, static::VALUE_TYPE_STRING));
        $relationshipsSchema->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_TYPE_STRING));

        return $relationshipsSchema;
    }

    /**
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createDefaultLinksSchema(): SchemaDataTransfer
    {
        $linksSchema = $this->schemaComponentBuilder->createSchemaDataTransfer(static::SCHEMA_NAME_LINKS);
        $linksSchema->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_SELF, static::VALUE_TYPE_STRING));

        return $linksSchema;
    }
}
