<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

interface SchemaBuilderInterface
{
    /**
     * @param string $responseDescriptionValue
     * @param array<string, mixed> $schemaStructure
     * @param bool $isRequired
     *
     * @return array<string, mixed>
     */
    public function buildResponse(
        string $responseDescriptionValue,
        array $schemaStructure,
        bool $isRequired = false
    ): array;

    /**
     * @param array<string, mixed> $fieldsArray
     *
     * @return array<string, mixed>
     */
    public function buildRequestRootOneOfItem(array $fieldsArray): array;

    /**
     * @param string $name
     * @param string $in
     * @param string $description
     * @param string $type
     * @param string|null $example
     *
     * @return array<string, mixed>
     */
    public function buildParameter(string $name, string $in, string $description, string $type, ?string $example = null): array;

    /**
     * @param array<string, mixed> $fieldsArray
     *
     * @return array<string, mixed>
     */
    public function buildResponseRootOneOfItem(array $fieldsArray): array;

    /**
     * @param array<string, mixed> $fieldsArray
     *
     * @return array<string, mixed>
     */
    public function buildRootOneOfItem(array $fieldsArray): array;

    /**
     * @param string $descriptionValue
     * @param string $codeOrHttpCode
     * @param array<string, mixed> $schemaStructure
     *
     * @return array<string, array<string, mixed>>
     */
    public function buildResponseArray(
        string $descriptionValue,
        string $codeOrHttpCode,
        array $schemaStructure
    ): array;

    /**
     * @param array<string, mixed> $fieldsArray
     * @param bool $isCollection
     *
     * @return array<string, mixed>
     */
    public function generateSchemaStructure(array $fieldsArray, bool $isCollection): array;

    /**
     * @param array<string, mixed> $oneOfFieldsArray
     * @param bool $isCollection
     *
     * @return array<string, mixed>
     */
    public function generateSchemaStructureOneOf(array $oneOfFieldsArray, bool $isCollection): array;
}
