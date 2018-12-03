<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Builder;

use Generated\Shared\Transfer\SchemaDataTransfer;
use Generated\Shared\Transfer\SchemaPropertyTransfer;

interface SchemaComponentBuilderInterface
{
    /**
     * @param string $key
     * @param string $schemaName
     * @param array $objectMetadata
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createObjectSchemaTypeTransfer(string $key, string $schemaName, array $objectMetadata): SchemaPropertyTransfer;

    /**
     * @param string $key
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createScalarSchemaTypeTransfer(string $key, string $type): SchemaPropertyTransfer;

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createSchemaDataTransfer(string $name): SchemaDataTransfer;

    /**
     * @param string $name
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createTypePropertyTransfer(string $name, string $type): SchemaPropertyTransfer;

    /**
     * @param string $name
     * @param string $ref
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createReferencePropertyTransfer(string $name, string $ref): SchemaPropertyTransfer;

    /**
     * @param string $name
     * @param string $itemsRef
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createArrayOfObjectsPropertyTransfer(string $name, string $itemsRef): SchemaPropertyTransfer;

    /**
     * @param string $name
     * @param string $itemsType
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createArrayOfTypesPropertyTransfer(string $name, string $itemsType): SchemaPropertyTransfer;

    /**
     * @param string $metadataKey
     * @param array $metadataValue
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createResponseSchemaPropertyTransfer(string $metadataKey, array $metadataValue): SchemaPropertyTransfer;

    /**
     * @param string $metadataKey
     * @param array $metadataValue
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createRequestSchemaPropertyTransfer(string $metadataKey, array $metadataValue): SchemaPropertyTransfer;
}
