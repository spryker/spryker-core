<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Processor;

use Generated\Shared\Transfer\SchemaDataTransfer;

interface ResourceRelationshipProcessorInterface
{
    /**
     * @param array<string> $relationships
     * @param string $transferClassName
     * @param string $responseDataSchemaName
     *
     * @return array<\Generated\Shared\Transfer\SchemaDataTransfer>
     */
    public function getRelationshipSchemaDataTransfers(
        array $relationships,
        string $transferClassName,
        string $responseDataSchemaName
    ): array;

    /**
     * @param string $transferClassName
     * @param array<string> $relationshipResponses
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function getIncludeDataSchema(
        string $transferClassName,
        array $relationshipResponses
    ): SchemaDataTransfer;

    /**
     * @param string $transferClassName
     * @param string $responseSchemaName
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function getIncludeBaseSchema(
        string $transferClassName,
        string $responseSchemaName
    ): SchemaDataTransfer;
}
