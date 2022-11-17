<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Builder;

use Generated\Shared\Transfer\SchemaDataTransfer;

interface SchemaBuilderInterface
{
    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRequestBaseSchema(string $schemaName, string $ref): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRequestDataSchema(string $schemaName, string $ref): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param array<mixed> $transferMetadata
     * @param bool $isSnakeCased
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRequestDataAttributesSchema(string $schemaName, array $transferMetadata, bool $isSnakeCased): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createResponseBaseSchema(string $schemaName, string $ref): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param string $ref
     * @param bool $isIdNullable
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createResponseDataSchema(string $schemaName, string $ref, bool $isIdNullable = false): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param array<mixed> $transferMetadata
     * @param bool $isSnakeCased
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createResponseDataAttributesSchema(string $schemaName, array $transferMetadata, bool $isSnakeCased): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createCollectionResponseBaseSchema(string $schemaName, string $ref): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRelationshipBaseSchema(string $schemaName, string $ref): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createIncludedBaseSchema(string $schemaName, string $ref): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param array<mixed> $resourceRelationships
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createRelationshipDataSchema(string $schemaName, array $resourceRelationships): SchemaDataTransfer;

    /**
     * @param string $schemaName
     * @param array<string> $relationshipResponses
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createIncludedDataSchema(string $schemaName, array $relationshipResponses): SchemaDataTransfer;

    /**
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createDefaultRelationshipDataAttributesSchema(): SchemaDataTransfer;

    /**
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createDefaultRelationshipDataCollectionAttributesSchema(): SchemaDataTransfer;

    /**
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createDefaultLinksSchema(): SchemaDataTransfer;
}
