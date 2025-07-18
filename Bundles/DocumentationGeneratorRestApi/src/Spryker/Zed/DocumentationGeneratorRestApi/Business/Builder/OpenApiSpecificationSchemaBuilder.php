<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder;

use Generated\Shared\Transfer\SchemaDataTransfer;

class OpenApiSpecificationSchemaBuilder implements SchemaBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_ATTRIBUTES = 'attributes';

    /**
     * @var string
     */
    protected const KEY_DATA = 'data';

    /**
     * @var string
     */
    protected const KEY_ID = 'id';

    /**
     * @var string
     */
    protected const KEY_LINKS = 'links';

    /**
     * @var string
     */
    protected const KEY_RELATIONSHIPS = 'relationships';

    /**
     * @var string
     */
    protected const KEY_INCLUDED = 'included';

    /**
     * @var string
     */
    protected const KEY_REST_REQUEST_PARAMETER = 'rest_request_parameter';

    /**
     * @var string
     */
    protected const KEY_REST_RESPONSE_PARAMETER = 'rest_response_parameter';

    /**
     * @var string
     */
    protected const METADATA_KEY_DESCRIPTION = 'description';

    /**
     * @var string
     */
    protected const METADATA_KEY_EXAMPLE = 'example';

    /**
     * @var string
     */
    protected const KEY_IS_NULLABLE = 'is_nullable';

    /**
     * @var string
     */
    protected const KEY_SELF = 'self';

    /**
     * @var string
     */
    protected const KEY_TYPE = 'type';

    /**
     * @var string
     */
    protected const VALUE_TYPE_STRING = 'string';

    /**
     * @var string
     */
    protected const VALUE_TYPE_ARRAY = 'array';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_LINKS = 'RestLinks';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_RELATIONSHIPS = 'RestRelationships';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_RELATIONSHIPS_DATA = 'RestRelationshipsData';

    /**
     * @var string
     */
    protected const REST_REQUEST_BODY_PARAMETER_REQUIRED = 'required';

    /**
     * @var string
     */
    protected const REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED = 'no';

    /**
     * @var string
     */
    protected const REST_RESPONSE_BODY_PARAMETER_REQUIRED = 'required';

    /**
     * @var string
     */
    protected const REST_RESPONSE_BODY_PARAMETER_NOT_REQUIRED = 'no';

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
     * @param string|null $type
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRequestDataSchema(string $schemaName, string $ref, ?string $type = null): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_TYPE_STRING, false, ['example' => $type]));
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

        foreach ($transferMetadata as $property => $metadata) {
            if ($metadata[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_NOT_REQUIRED) {
                continue;
            }

            if ($metadata[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_REQUIRED) {
                $schemaData->addRequired($property);
            }

            $schemaData->addProperty($this->schemaComponentBuilder->createRequestSchemaPropertyTransfer($property, $metadata));
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
     * @param string|null $type
     * @param bool $isIdNullable
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createResponseDataSchema(string $schemaName, string $ref, ?string $type = null, bool $isIdNullable = false): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createTypePropertyTransfer(static::KEY_TYPE, static::VALUE_TYPE_STRING, false, ['example' => $type, 'description' => 'The name of the resource.']));
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
            if (isset($value[static::KEY_REST_RESPONSE_PARAMETER]) && ($value[static::KEY_REST_RESPONSE_PARAMETER] === static::REST_RESPONSE_BODY_PARAMETER_NOT_REQUIRED)) {
                continue;
            }

            $schemaData->addProperty($this->schemaComponentBuilder->createResponseSchemaPropertyTransfer($key, $value));

            if ($value[static::KEY_REST_RESPONSE_PARAMETER] === static::REST_RESPONSE_BODY_PARAMETER_REQUIRED) {
                $schemaData->addRequired($key);
            }
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
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createIncludedBaseSchema(string $schemaName, string $ref): SchemaDataTransfer
    {
        $schemaData = $this->schemaComponentBuilder->createSchemaDataTransfer($schemaName);
        $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer(static::KEY_INCLUDED, $ref));

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
            $schemaData->addProperty($this->schemaComponentBuilder->createReferencePropertyTransfer($resourceRelationship, static::SCHEMA_NAME_RELATIONSHIPS_DATA));
        }

        return $schemaData;
    }

    /**
     * @param string $schemaName
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface> $resourceRelationships
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createIncludedDataSchema(string $schemaName, array $resourceRelationships): SchemaDataTransfer
    {
        return $this->schemaComponentBuilder
            ->createSchemaDataTransfer($schemaName)
            ->setType(static::VALUE_TYPE_ARRAY)
            ->setItems($this->schemaComponentBuilder->createRelationshipSchemaItemsTransfer($resourceRelationships));
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
    public function createDefaultRelationshipDataCollectionAttributesSchema(): SchemaDataTransfer
    {
        $relationshipDataSchema = $this->schemaComponentBuilder->createSchemaDataTransfer(static::SCHEMA_NAME_RELATIONSHIPS_DATA);
        $relationshipDataSchema->addProperty($this->schemaComponentBuilder->createArrayOfObjectsPropertyTransfer(static::KEY_DATA, static::SCHEMA_NAME_RELATIONSHIPS));

        return $relationshipDataSchema;
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
