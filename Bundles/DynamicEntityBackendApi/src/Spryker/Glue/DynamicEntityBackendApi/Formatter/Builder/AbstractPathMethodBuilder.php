<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Spryker\Glue\DynamicEntityBackendApi\Exception\MissingFieldDefinitionException;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder\DynamicEntityConfigurationTreeBuilderInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPathMethodBuilder implements PathMethodBuilderInterface
{
    /**
     * @var string
     */
    protected const TAG_TPL = 'dynamic-entity-%s';

    /**
     * @var string
     */
    protected const DESCRIPTION_PARAMETER_ID = 'ID of entity %s';

    /**
     * @var string
     */
    protected const RESPONSE_ERROR_DEFAULT_MESSAGE = 'An error occurred.';

    /**
     * @var string
     */
    protected const RESPONSE_ERROR_NOT_FOUND_MESSAGE = 'Not Found.';

    /**
     * @var string
     */
    protected const RESPONSE_ERROR_UNAUTHORIZED_REQUEST_MESSAGE = 'Unauthorized request.';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT = 'Accept';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT_DESCRIPTION = 'The Accept request HTTP header indicates which content types, expressed as MIME types, the client is able to understand.';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE_DESCRIPTION = 'Content type of request body.';

    /**
     * @var string
     */
    protected const HEADER = 'header';

    /**
     * @var string
     */
    protected const APPLICATION_JSON = 'application/json';

    /**
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var string
     */
    protected const ID = 'id';

    /**
     * @var string
     */
    protected const MISSING_FIELD_DEFINITIONS_EXCEPTION_MESSAGE = 'No fields defined for dynamic entity.';

    /**
     * @var string
     */
    protected const FLOAT = 'float';

    /**
     * @var string
     */
    protected const NUMBER = 'number';

    /**
     * @var string
     */
    protected const FORMAT = 'format';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    abstract public function buildPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig
     */
    protected DynamicEntityBackendApiConfig $config;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder\DynamicEntityConfigurationTreeBuilderInterface
     */
    protected DynamicEntityConfigurationTreeBuilderInterface $treeBuilder;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\SchemaBuilderInterface
     */
    protected SchemaBuilderInterface $schemaBuilder;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig $config
     * @param \Spryker\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder\DynamicEntityConfigurationTreeBuilderInterface $treeBuilder
     * @param \Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\SchemaBuilderInterface $schemaBuilder
     */
    public function __construct(
        DynamicEntityBackendApiConfig $config,
        DynamicEntityConfigurationTreeBuilderInterface $treeBuilder,
        SchemaBuilderInterface $schemaBuilder
    ) {
        $this->config = $config;
        $this->treeBuilder = $treeBuilder;
        $this->schemaBuilder = $schemaBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param bool $skipIdentifier
     * @param bool $filterIsCreatable
     * @param bool $filterIsEditable
     *
     * @throws \Spryker\Glue\DynamicEntityBackendApi\Exception\MissingFieldDefinitionException
     *
     * @return array<mixed>
     */
    protected function prepareFieldsArray(
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        bool $skipIdentifier = false,
        bool $filterIsCreatable = false,
        bool $filterIsEditable = false
    ): array {
        $result = [];

        $fieldDefinitions = $dynamicEntityDefinitionTransfer->getFieldDefinitions();

        if ($fieldDefinitions->count() === 0) {
            throw new MissingFieldDefinitionException(static::MISSING_FIELD_DEFINITIONS_EXCEPTION_MESSAGE);
        }

        /**
         * @var \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $field
         */
        foreach ($fieldDefinitions as $field) {
            $isFieldIdentifier = $dynamicEntityDefinitionTransfer->getIdentifierOrFail() === $field->getFieldNameOrFail();
            if ($skipIdentifier === true && $isFieldIdentifier === true) {
                continue;
            }

            if (
                !$isFieldIdentifier &&
                ($filterIsCreatable && !$field->getIsCreatable() || $filterIsEditable && !$field->getIsEditable())
            ) {
                continue;
            }

            $result[$field->getFieldVisibleNameOrFail()] = $this->buildFieldType($field);
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return int
     */
    protected function calculateDeepLevelInConfiguration(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): int
    {
        $deepLevel = 0;
        /** @var \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $childRelation */
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $deepLevel = max($deepLevel, $this->calculateDeepLevelInConfiguration($childRelation->getChildDynamicEntityConfigurationOrFail()));
        }

        return $deepLevel + 1;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param bool $skipIdentifier
     * @param bool $filterIsCreatable
     * @param bool $filterIsEditable
     *
     * @return array<mixed>
     */
    protected function buildOneOfCombinationArrayRecursively(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        bool $skipIdentifier = false,
        bool $filterIsCreatable = false,
        bool $filterIsEditable = false
    ): array {
        $dynamicEntityConfigurationTransfers = $this->buildDynamicEntityConfigurationTransferWithCombinations($dynamicEntityConfigurationTransfer);

        $oneOfCombinationsFields = [];

        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $oneOfCombinationsFields[] = $this->schemaBuilder->buildRootOneOfItem(
                $this->prepareFieldsArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreatable,
                    $filterIsEditable,
                ),
            );
        }

        return $oneOfCombinationsFields;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param bool $skipIdentifier
     * @param bool $filterIsCreatable
     * @param bool $filterIsEditable
     *
     * @return array<string, mixed>
     */
    protected function prepareFieldsArrayRecursively(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        bool $skipIdentifier = false,
        bool $filterIsCreatable = false,
        bool $filterIsEditable = false
    ): array {
        $fields = $this->prepareFieldsArray(
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
            $skipIdentifier,
            $filterIsCreatable,
            $filterIsEditable,
        );

        /** @var \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $childRelation */
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $fields[$childRelation->getNameOrFail()] = [
                static::KEY_TYPE => static::SCHEMA_TYPE_ARRAY,
                static::KEY_SCHEMA_ITEMS => [
                    static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                    static::KEY_SCHEMA_PROPERTIES => $this->prepareFieldsArrayRecursively(
                        $childRelation->getChildDynamicEntityConfigurationOrFail(),
                        $skipIdentifier,
                        $filterIsCreatable,
                        $filterIsEditable,
                    ),
                ],
            ];
        }

        return $fields;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildFieldType(DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): array
    {
        $type = $dynamicEntityFieldDefinitionTransfer->getTypeOrFail();
        $filedType = $this->buildKeyType($type);

        $dynamicEntityFieldValidationTransfer = $dynamicEntityFieldDefinitionTransfer->getValidation();

        if ($dynamicEntityFieldValidationTransfer === null) {
            return $filedType;
        }

        return $this->addValidationToFieldType($filedType, $dynamicEntityFieldValidationTransfer);
    }

    /**
     * @param array<string, mixed> $filedType
     * @param \Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer $dynamicEntityFieldValidationTransfer
     *
     * @return array<string, mixed>
     */
    protected function addValidationToFieldType(
        array $filedType,
        DynamicEntityFieldValidationTransfer $dynamicEntityFieldValidationTransfer
    ): array {
        if ($dynamicEntityFieldValidationTransfer->getMin() !== null) {
            $filedType[static::KEY_TYPE_MIN] = $dynamicEntityFieldValidationTransfer->getMin();
        }

        if ($dynamicEntityFieldValidationTransfer->getMax() !== null) {
            $filedType[static::KEY_TYPE_MAX] = $dynamicEntityFieldValidationTransfer->getMax();
        }

        if ($dynamicEntityFieldValidationTransfer->getMinLength() !== null) {
            $filedType[static::KEY_TYPE_MIN_LENGTH] = $dynamicEntityFieldValidationTransfer->getMinLength();
        }

        if ($dynamicEntityFieldValidationTransfer->getMaxLength() !== null) {
            $filedType[static::KEY_TYPE_MAX_LENGTH] = $dynamicEntityFieldValidationTransfer->getMaxLength();
        }

        if ($dynamicEntityFieldValidationTransfer->getPrecision() !== null) {
            $filedType[static::KEY_TYPE_MIN_LENGTH] = 1;
            $filedType[static::KEY_TYPE_MAX_LENGTH] = $dynamicEntityFieldValidationTransfer->getPrecision() + 1;
        }

        return $filedType;
    }

    /**
     * @param string $fieldType
     *
     * @return array<string, mixed>
     */
    protected function buildKeyType(string $fieldType): array
    {
        $keyType = [static::KEY_TYPE => $fieldType === static::FLOAT ? static::NUMBER : $fieldType];

        if ($fieldType === static::FLOAT) {
            $keyType[static::FORMAT] = static::FLOAT;
        }

        return $keyType;
    }

    /**
     * @param string $placeholder
     * @param string $resourceName
     *
     * @return string
     */
    protected function formatPath(string $placeholder, string $resourceName): string
    {
        return str_replace('//', '/', sprintf($placeholder, $this->config->getRoutePrefix(), $resourceName));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getTag(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        return sprintf(static::TAG_TPL, $dynamicEntityConfigurationTransfer->getTableAliasOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildIdParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return $this->schemaBuilder->buildParameter(
            static::ID,
            static::PATH,
            sprintf(static::DESCRIPTION_PARAMETER_ID, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
            static::SCHEMA_TYPE_INTEGER,
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildHeaderContentTypeParameter(): array
    {
        return $this->schemaBuilder->buildParameter(
            static::HEADER_CONTENT_TYPE,
            static::HEADER,
            static::HEADER_CONTENT_TYPE_DESCRIPTION,
            static::SCHEMA_TYPE_STRING,
            static::APPLICATION_JSON,
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildHeaderAcceptParameter(): array
    {
        return $this->schemaBuilder->buildParameter(
            static::HEADER_ACCEPT,
            static::HEADER,
            static::HEADER_ACCEPT_DESCRIPTION,
            static::SCHEMA_TYPE_STRING,
            static::APPLICATION_JSON,
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildResponseDefault(): array
    {
        return [
            static::KEY_RESPONSE_DEFAULT => $this->schemaBuilder->buildResponse(static::RESPONSE_ERROR_DEFAULT_MESSAGE, [static::KEY_SCHEMA_REF => static::SCHEMA_REF_COMPONENT_REST_ERROR]),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseNotFound(): array
    {
        return [
            (string)Response::HTTP_NOT_FOUND => $this->schemaBuilder->buildResponse(static::RESPONSE_ERROR_NOT_FOUND_MESSAGE, [static::KEY_SCHEMA_REF => static::SCHEMA_REF_COMPONENT_REST_ERROR]),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseUnauthorizedRequest(): array
    {
        return [
            (string)Response::HTTP_FORBIDDEN => $this->schemaBuilder->buildResponse(static::RESPONSE_ERROR_UNAUTHORIZED_REQUEST_MESSAGE, [static::KEY_SCHEMA_REF => static::SCHEMA_REF_COMPONENT_REST_ERROR]),
        ];
    }

    /**
     * @param string $responseDescriptionValue
     * @param array<string, mixed> $fieldsArray
     * @param string $code
     * @param bool $isCollection
     * @param bool $isOneOf
     *
     * @return array<string, array<string, mixed>>
     */
    protected function buildSuccessResponse(
        string $responseDescriptionValue,
        array $fieldsArray,
        string $code,
        bool $isCollection = false,
        bool $isOneOf = false
    ): array {
        $schemaStructure = $this->buildSchemaStructure($fieldsArray, $isCollection, $isOneOf);

        return $this->schemaBuilder->buildResponseArray($responseDescriptionValue, $code, $schemaStructure);
    }

    /**
     * @param string $description
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param bool $skipIdentifier
     * @param bool $filterIsCreatable
     * @param bool $filterIsEditable
     *
     * @return array<string, mixed>
     */
    protected function buildRequest(
        string $description,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        bool $skipIdentifier = false,
        bool $filterIsCreatable = false,
        bool $filterIsEditable = false
    ): array {
        if (!$this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildRequestBody(
                $description,
                $this->prepareFieldsArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreatable,
                    $filterIsEditable,
                ),
            );
        }

        return $this->buildRequestBody(
            $description,
            $this->buildOneOfRequestItems(
                $this->buildDynamicEntityConfigurationTransferWithCombinations($dynamicEntityConfigurationTransfer),
            ),
            false,
            true,
        );
    }

    /**
     * @param string $descriptionValue
     * @param array<string, mixed> $fieldsArray
     * @param bool $isCollection
     * @param bool $isOneOf
     *
     * @return array<string, mixed>
     */
    protected function buildRequestBody(
        string $descriptionValue,
        array $fieldsArray,
        bool $isCollection = false,
        bool $isOneOf = false
    ): array {
        $schemaStructure = $this->buildSchemaStructure($fieldsArray, $isCollection, $isOneOf);

        return [
            static::KEY_REQUEST_BODY => $this->schemaBuilder->buildResponse($descriptionValue, $schemaStructure, true),
        ];
    }

    /**
     * @param array<string, mixed> $fieldsArray
     * @param bool $isCollection
     * @param bool $isOneOf
     *
     * @return array<string, mixed>
     */
    protected function buildSchemaStructure(
        array $fieldsArray,
        bool $isCollection = false,
        bool $isOneOf = false
    ): array {
        if ($isOneOf === true) {
            return $this->schemaBuilder->generateSchemaStructureOneOf($fieldsArray, $isCollection);
        }

        return $this->schemaBuilder->generateSchemaStructure($fieldsArray, $isCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $operationId
     * @param string $summary
     *
     * @return array<string, mixed>
     */
    protected function expandPathData(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $operationId,
        string $summary
    ): array {
        return [
            static::KEY_TAGS => [$this->getTag($dynamicEntityConfigurationTransfer)],
            static::KEY_OPERATION_ID => sprintf(
                $operationId,
                $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
            ), static::KEY_SUMMARY => sprintf($summary, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildKeyParameters(?DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer = null): array
    {
        if ($dynamicEntityConfigurationTransfer !== null) {
            return [
                static::KEY_PARAMETERS => [
                    $this->buildIdParameter($dynamicEntityConfigurationTransfer),
                    $this->buildHeaderContentTypeParameter(),
                    $this->buildHeaderAcceptParameter(),
                ],
            ];
        }

        return [
            static::KEY_PARAMETERS => [
                $this->buildHeaderContentTypeParameter(),
                $this->buildHeaderAcceptParameter(),
            ],
        ];
    }

    /**
     * @param array<mixed> $responses
     *
     * @return array<string, mixed>
     */
    protected function buildResponses(array $responses): array
    {
        return [
            static::KEY_RESPONSES => $responses,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return bool
     */
    protected function haveChildRelations(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): bool
    {
        return $dynamicEntityConfigurationTransfer->getChildRelations()->count() > 0;
    }

    /**
     * @param string $responseDescriptionValue
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $httpCode
     *
     * @return array<mixed>
     */
    protected function buildSuccessResponseBody(
        string $responseDescriptionValue,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $httpCode
    ): array {
        if (!$this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildSuccessResponse(
                $responseDescriptionValue,
                $this->prepareFieldsArrayRecursively($dynamicEntityConfigurationTransfer),
                $httpCode,
            );
        }

        return $this->buildSuccessResponse(
            $responseDescriptionValue,
            $this->buildOneOfResponseItems(
                $this->buildDynamicEntityConfigurationTransferWithCombinations($dynamicEntityConfigurationTransfer),
            ),
            $httpCode,
            false,
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function buildDynamicEntityConfigurationTransferWithCombinations(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $deepLevel = $this->calculateDeepLevelInConfiguration($dynamicEntityConfigurationTransfer);

        $dynamicEntityConfigurationTransfers = [];

        while ($deepLevel > -1) {
            $copyDynamicEntityConfigurationTransfer = (new DynamicEntityConfigurationTransfer())->fromArray($dynamicEntityConfigurationTransfer->toArray(), true);
            $copyDynamicEntityConfigurationTransfer = $this->treeBuilder->buildDynamicEntityConfigurationTransferTree($copyDynamicEntityConfigurationTransfer, $deepLevel);
            $dynamicEntityConfigurationTransfers[] = $copyDynamicEntityConfigurationTransfer;
            $deepLevel--;
        }

        return $dynamicEntityConfigurationTransfers;
    }

    /**
     *
     * @param array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     * @param bool $skipIdentifier
     * @param bool $filterIsCreatable
     * @param bool $filterIsEditable
     *
     * @return array<mixed>
     */
    protected function buildOneOfResponseItems(
        array $dynamicEntityConfigurationTransfers,
        bool $skipIdentifier = false,
        bool $filterIsCreatable = false,
        bool $filterIsEditable = false
    ): array {
        $items = [];
        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $items[] = $this->schemaBuilder->buildResponseRootOneOfItem(
                $this->prepareFieldsArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreatable,
                    $filterIsEditable,
                ),
            );
        }

        return $items;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getRequestDescription(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        if (!$this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return '';
        }

        return sprintf(
            ' Request data can contain also child relation, for example: `{ ...fields, %s: { ...childFields } }`.',
            $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getNameOrFail(),
        );
    }

    /**
     *
     * @param array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     * @param bool $skipIdentifier
     * @param bool $filterIsCreatable
     * @param bool $filterIsEditable
     *
     * @return array<mixed>
     */
    protected function buildOneOfRequestItems(
        array $dynamicEntityConfigurationTransfers,
        bool $skipIdentifier = false,
        bool $filterIsCreatable = false,
        bool $filterIsEditable = false
    ): array {
        $items = [];
        /** @var \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer */
        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $items[] = $this->schemaBuilder->buildRequestRootOneOfItem(
                $this->prepareFieldsArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreatable,
                    $filterIsEditable,
                ),
            );
        }

        return $items;
    }
}
