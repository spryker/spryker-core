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
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createScalarSchemaTypeTransfer(string $key, string $type, bool $isNullable = false): SchemaPropertyTransfer;

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\SchemaDataTransfer
     */
    public function createSchemaDataTransfer(string $name): SchemaDataTransfer;

    /**
     * @param string $name
     * @param string $type
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createTypePropertyTransfer(string $name, string $type, bool $isNullable = false): SchemaPropertyTransfer;

    /**
     * @param string $name
     * @param string $ref
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createReferencePropertyTransfer(string $name, string $ref, bool $isNullable = false): SchemaPropertyTransfer;

    /**
     * @param string $name
     * @param string $itemsRef
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createArrayOfObjectsPropertyTransfer(string $name, string $itemsRef, bool $isNullable = false): SchemaPropertyTransfer;

    /**
     * @param string $name
     * @param string $itemsType
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createArrayOfTypesPropertyTransfer(string $name, string $itemsType, bool $isNullable = false): SchemaPropertyTransfer;

    /**
     * @param string $name
     * @param bool $isNullable
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyTransfer
     */
    public function createArrayOfMixedTypesPropertyTransfer(string $name, bool $isNullable = false): SchemaPropertyTransfer;

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
