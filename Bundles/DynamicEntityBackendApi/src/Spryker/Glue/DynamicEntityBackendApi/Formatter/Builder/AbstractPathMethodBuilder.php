<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use ArrayObject;
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
    protected const HEADER = 'header';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    abstract public function buildPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig $config
     * @param \Spryker\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder\DynamicEntityConfigurationTreeBuilderInterface $treeBuilder
     * @param \Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\SchemaBuilderInterface $schemaBuilder
     */
    public function __construct(
        protected DynamicEntityBackendApiConfig $config,
        protected DynamicEntityConfigurationTreeBuilderInterface $treeBuilder,
        protected SchemaBuilderInterface $schemaBuilder
    ) {
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

        $dynamicEntityFieldDefinitionTransfers = $dynamicEntityDefinitionTransfer->getFieldDefinitions();

        if ($dynamicEntityFieldDefinitionTransfers->count() === 0) {
            throw new MissingFieldDefinitionException('No fields defined for dynamic entity.');
        }

        /** @var \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer */
        foreach ($dynamicEntityFieldDefinitionTransfers as $dynamicEntityFieldDefinitionTransfer) {
            $isFieldIdentifier = $dynamicEntityDefinitionTransfer->getIdentifierOrFail() === $dynamicEntityFieldDefinitionTransfer->getFieldNameOrFail();

            if ($skipIdentifier === true && $isFieldIdentifier === true) {
                continue;
            }

            if (
                !$isFieldIdentifier &&
                ($filterIsCreatable && !$dynamicEntityFieldDefinitionTransfer->getIsCreatable() || $filterIsEditable && !$dynamicEntityFieldDefinitionTransfer->getIsEditable())
            ) {
                continue;
            }

            $result[$dynamicEntityFieldDefinitionTransfer->getFieldVisibleNameOrFail()] = $this->createEnhancedPropertyDefinition($dynamicEntityFieldDefinitionTransfer, $dynamicEntityDefinitionTransfer);
        }

        return $result;
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
        return [
            $this->schemaBuilder->buildRootOneOfItem(
                $this->prepareFieldsArrayWithChildren(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreatable,
                    $filterIsEditable,
                ),
            ),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param bool $skipIdentifier
     * @param bool $filterIsCreatable
     * @param bool $filterIsEditable
     *
     * @return array<string, mixed>
     */
    protected function prepareFieldsArrayWithChildren(
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
                'type' => static::SCHEMA_TYPE_ARRAY,
                static::KEY_SCHEMA_ITEMS => [
                    'type' => static::SCHEMA_TYPE_OBJECT,
                    static::KEY_SCHEMA_PROPERTIES => $this->prepareFieldsArray(
                        $childRelation->getChildDynamicEntityConfigurationOrFail()->getDynamicEntityDefinitionOrFail(),
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
    protected function buildEnrichedPropertyDefinition(DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): array
    {
        $propertyDefinition = $this->buildPropertyDefinition($dynamicEntityFieldDefinitionTransfer);

        $dynamicEntityFieldValidationTransfer = $dynamicEntityFieldDefinitionTransfer->getValidation();

        if ($dynamicEntityFieldValidationTransfer === null) {
            return $this->enrichPropertyDefinitionWithMetadata($propertyDefinition, $dynamicEntityFieldDefinitionTransfer);
        }

        $propertyDefinition = $this->addValidationToPropertyDefinition($propertyDefinition, $dynamicEntityFieldValidationTransfer);

        return $this->enrichPropertyDefinitionWithMetadata($propertyDefinition, $dynamicEntityFieldDefinitionTransfer);
    }

    /**
     * @param array<string, mixed> $propertyDefinition
     * @param \Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer $dynamicEntityFieldValidationTransfer
     *
     * @return array<string, mixed>
     */
    protected function addValidationToPropertyDefinition(
        array $propertyDefinition,
        DynamicEntityFieldValidationTransfer $dynamicEntityFieldValidationTransfer
    ): array {
        if ($dynamicEntityFieldValidationTransfer->getMin() !== null) {
            $propertyDefinition['minimum'] = $dynamicEntityFieldValidationTransfer->getMin();
        }

        if ($dynamicEntityFieldValidationTransfer->getMax() !== null) {
            $propertyDefinition['maximum'] = $dynamicEntityFieldValidationTransfer->getMax();
        }

        if ($dynamicEntityFieldValidationTransfer->getMinLength() !== null) {
            $propertyDefinition['minLength'] = $dynamicEntityFieldValidationTransfer->getMinLength();
        }

        if ($dynamicEntityFieldValidationTransfer->getMaxLength() !== null) {
            $propertyDefinition['maxLength'] = $dynamicEntityFieldValidationTransfer->getMaxLength();
        }

        if ($dynamicEntityFieldValidationTransfer->getPrecision() !== null) {
            $propertyDefinition['minLength'] = 1;
            $propertyDefinition['maxLength'] = $dynamicEntityFieldValidationTransfer->getPrecision() + 1;
        }

        return $this->addAdvancedValidationRules($propertyDefinition, $dynamicEntityFieldValidationTransfer);
    }

    /**
     * @param array<string, mixed> $propertyDefinition
     * @param \Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer $dynamicEntityFieldValidationTransfer
     *
     * @return array<string, mixed>
     */
    protected function addAdvancedValidationRules(
        array $propertyDefinition,
        DynamicEntityFieldValidationTransfer $dynamicEntityFieldValidationTransfer
    ): array {
        // Add scale information for numeric fields
        if ($dynamicEntityFieldValidationTransfer->getScale() !== null) {
            $propertyDefinition['multipleOf'] = pow(10, -$dynamicEntityFieldValidationTransfer->getScale());
        }

        // Add constraint information if available
        if ($dynamicEntityFieldValidationTransfer->getConstraints() !== null && $dynamicEntityFieldValidationTransfer->getConstraints()->count() > 0) {
            $constraints = [];

            /** @var \Generated\Shared\Transfer\DynamicEntityFieldValidationConstraintTransfer $constraint */
            foreach ($dynamicEntityFieldValidationTransfer->getConstraints() as $constraint) {
                $constraints[] = $constraint->getNameOrFail();
            }

            $propertyDefinition['constraints'] = $constraints;
        }

        return $propertyDefinition;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildPropertyDefinition(DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): array
    {
        $type = $dynamicEntityFieldDefinitionTransfer->getTypeOrFail();

        $propertyDefinition = ['type' => $type === 'float' ? 'number' : $type];

        if ($type === 'float') {
            $propertyDefinition['format'] = 'float';
        }

        // Add format for other common types
        if ($type === 'datetime') {
            $propertyDefinition['type'] = 'string';
            $propertyDefinition['format'] = 'date-time';
        }

        if ($type === 'date') {
            $propertyDefinition['type'] = 'string';
            $propertyDefinition['format'] = 'date';
        }

        if ($type === 'email') {
            $propertyDefinition['type'] = 'string';
            $propertyDefinition['format'] = 'email';
        }

        if ($type === 'uuid' || str_contains($type, 'uuid')) {
            $propertyDefinition['type'] = 'string';
            $propertyDefinition['format'] = 'uuid';
        }

        return $propertyDefinition;
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
        return sprintf('dynamic-entity-%s', $dynamicEntityConfigurationTransfer->getTableAliasOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildIdParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return $this->schemaBuilder->buildParameter(
            'id',
            'path',
            sprintf('ID of entity %s', $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
            static::SCHEMA_TYPE_INTEGER,
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildHeaderContentTypeParameter(): array
    {
        return $this->schemaBuilder->buildParameter(
            'Content-Type',
            'header',
            'Content type of request body.',
            'string',
            'application/json',
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildHeaderAcceptParameter(): array
    {
        return $this->schemaBuilder->buildParameter(
            'Accept',
            'header',
            'The Accept request HTTP header indicates which content types, expressed as MIME types, the client is able to understand.',
            'string',
            'application/json',
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildResponseDefault(): array
    {
        return [
            static::KEY_RESPONSE_DEFAULT => $this->schemaBuilder->buildResponse('An error occurred.', ['$ref' => static::SCHEMA_REF_COMPONENT_REST_ERROR]),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseNotFound(): array
    {
        return [
            (string)Response::HTTP_NOT_FOUND => $this->schemaBuilder->buildResponse('Not Found.', ['$ref' => static::SCHEMA_REF_COMPONENT_REST_ERROR]),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseNoContent(): array
    {
        return [
            (string)Response::HTTP_NO_CONTENT => $this->schemaBuilder->buildResponse('No content.'),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseMethodNotAllowed(): array
    {
        return [
            (string)Response::HTTP_METHOD_NOT_ALLOWED => $this->schemaBuilder->buildResponse('Method not allowed.', ['$ref' => static::SCHEMA_REF_COMPONENT_REST_ERROR]),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseUnauthorizedRequest(): array
    {
        return [
            (string)Response::HTTP_FORBIDDEN => $this->schemaBuilder->buildResponse('Unauthorized request.', ['$ref' => static::SCHEMA_REF_COMPONENT_REST_ERROR]),
        ];
    }

    /**
     * @param string $responseDescriptionValue
     * @param array<string, mixed> $fieldsArray
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $dynamicEntityFieldDefinitionTransfers
     * @param string $code
     * @param bool $isCollection
     * @param bool $isOneOf
     *
     * @return array<string, array<string, mixed>>
     */
    protected function buildSuccessResponse(
        string $responseDescriptionValue,
        array $fieldsArray,
        ArrayObject $dynamicEntityFieldDefinitionTransfers,
        string $code,
        bool $isCollection = false,
        bool $isOneOf = false
    ): array {
        $schemaStructure = $this->buildSchemaStructure($fieldsArray, $dynamicEntityFieldDefinitionTransfers, $isCollection, $isOneOf);

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
                $this->prepareFieldsArrayWithChildren(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreatable,
                    $filterIsEditable,
                ),
                $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions(),
            );
        }

        return $this->buildRequestBody(
            $description,
            $this->buildOneOfRequestItems(
                [$dynamicEntityConfigurationTransfer],
            ),
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions(),
            false,
            true,
        );
    }

    /**
     * @param string $descriptionValue
     * @param array<string, mixed> $fieldsArray
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $dynamicEntityDefinitionFieldTransfers
     * @param bool $isCollection
     * @param bool $isOneOf
     *
     * @return array<string, mixed>
     */
    protected function buildRequestBody(
        string $descriptionValue,
        array $fieldsArray,
        ArrayObject $dynamicEntityDefinitionFieldTransfers,
        bool $isCollection = false,
        bool $isOneOf = false
    ): array {
        $schemaStructure = $this->buildSchemaStructure($fieldsArray, $dynamicEntityDefinitionFieldTransfers, $isCollection, $isOneOf);

        return [
            static::KEY_REQUEST_BODY => $this->schemaBuilder->buildResponse($descriptionValue, $schemaStructure, true),
        ];
    }

    /**
     * @param array<string, mixed> $fieldsArray
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $dynamicEntityDefinitionFieldTransfers
     * @param bool $isCollection
     * @param bool $isOneOf
     *
     * @return array<string, mixed>
     */
    protected function buildSchemaStructure(
        array $fieldsArray,
        ArrayObject $dynamicEntityDefinitionFieldTransfers,
        bool $isCollection = false,
        bool $isOneOf = false
    ): array {
        if ($isOneOf === true) {
            return $this->schemaBuilder->generateSchemaStructureOneOf($fieldsArray, $dynamicEntityDefinitionFieldTransfers, $isCollection);
        }

        return $this->schemaBuilder->generateSchemaStructure($fieldsArray, $dynamicEntityDefinitionFieldTransfers, $isCollection);
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
            $dynamicEntityConfigurationTransfer->getChildRelations()->offsetGet(0)->getNameOrFail(),
        );
    }

    /**
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

        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $items[] = $this->schemaBuilder->buildRequestRootOneOfItem(
                $this->prepareFieldsArrayWithChildren(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreatable,
                    $filterIsEditable,
                ),
                $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions(),
            );
        }

        return $items;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildFilterParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $resourceName = $dynamicEntityConfigurationTransfer->getTableAliasOrFail();

        $properties = [];

        /** @var \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinition */
        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $fieldDefinition) {
            $propertyKey = sprintf('%s.%s', $resourceName, $fieldDefinition->getFieldVisibleNameOrFail());
            $properties[$propertyKey] = $this->buildPropertyDefinition($fieldDefinition);
        }

        return [
            static::KEY_NAME => 'filter',
            static::KEY_IN => 'query',
            static::KEY_DESCRIPTION => 'Parameter is used to filter items by specified values.',
            static::KEY_REQUIRED => false,
            static::KEY_STYLE => 'deepObject',
            static::KEY_EXPLODE => true,
            static::KEY_SCHEMA => [
                'type' => static::SCHEMA_TYPE_OBJECT,
                static::KEY_SCHEMA_PROPERTIES => $properties,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $propertyDefinition
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return array<string, mixed>
     */
    protected function enrichPropertyDefinitionWithMetadata(
        array $propertyDefinition,
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
    ): array {
        // Add description if available
        if ($dynamicEntityFieldDefinitionTransfer->getDescription() !== null) {
            $propertyDefinition['description'] = $dynamicEntityFieldDefinitionTransfer->getDescription();
        }

        $example = $this->generatePropertyExample($dynamicEntityFieldDefinitionTransfer);

        // Add examples if available, otherwise use contextual example
        if ($dynamicEntityFieldDefinitionTransfer->getExamples() !== null) {
            $example = $dynamicEntityFieldDefinitionTransfer->getExamples();
        }

        if ($example) {
            $examples = explode(', ', $example);

            if (count($examples) === 1) {
                $propertyDefinition['example'] = current($examples);
            }

            if (count($examples) > 1) {
                $propertyDefinition['example'] = $examples;
            }
        }

        if ($dynamicEntityFieldDefinitionTransfer->getEnumValues()) {
            $propertyDefinition['enum'] = explode(', ', $dynamicEntityFieldDefinitionTransfer->getEnumValues());
        }

        // Add title using field visible name if available
        if ($dynamicEntityFieldDefinitionTransfer->getFieldVisibleName() !== null) {
            $propertyDefinition['title'] = $dynamicEntityFieldDefinitionTransfer->getFieldVisibleName();
        }

        return $propertyDefinition;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<mixed>
     */
    protected function createEnhancedPropertyDefinition(
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
    ): array {
        $fieldSchema = $this->buildEnrichedPropertyDefinition($dynamicEntityFieldDefinitionTransfer);

        // Add enhanced metadata for better OpenAPI documentation
        $fieldName = $dynamicEntityFieldDefinitionTransfer->getFieldNameOrFail();
        $isIdentifier = $dynamicEntityDefinitionTransfer->getIdentifierOrFail() === $fieldName;

        if ($isIdentifier) {
            $fieldSchema['description'] = $fieldSchema['description'] ?? 'Unique identifier for the entity';
            $fieldSchema['readOnly'] = true;
        }

        // Add field-specific descriptions if not already present
        if (!isset($fieldSchema['description'])) {
            $fieldSchema['description'] = $this->generatePropertyDescription($dynamicEntityFieldDefinitionTransfer);
        }

        return $fieldSchema;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return string
     */
    protected function generatePropertyDescription(DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): string
    {
        $fieldName = $dynamicEntityFieldDefinitionTransfer->getFieldVisibleNameOrFail();
        $type = $dynamicEntityFieldDefinitionTransfer->getTypeOrFail();

        $description = ucfirst($fieldName);

        // Add type-specific information
        if ($type === 'datetime') {
            $description .= ' (ISO 8601 format)';
        }

        if ($type === 'email') {
            $description .= ' (valid email address)';
        }

        if ($type === 'uuid') {
            $description .= ' (UUID format)';
        }

        // Add editability information
        if (!$dynamicEntityFieldDefinitionTransfer->getIsEditable()) {
            $description .= ' (read-only)';
        }

        return $description;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinition
     *
     * @return string|null
     */
    protected function generatePropertyExample(DynamicEntityFieldDefinitionTransfer $fieldDefinition): ?string
    {
        $type = $fieldDefinition->getTypeOrFail();
        $fieldName = $fieldDefinition->getFieldNameOrFail();

        switch ($type) {
            case 'string':
                return $this->generateStringExample($fieldName);
            case 'integer':
                return '123';
            case 'float':
                return '123.45';
            case 'boolean':
                return 'true';
            case 'datetime':
                return '2023-01-01T12:00:00Z';
            case 'date':
                return '2023-01-01';
            case 'email':
                return 'user@example.com';
            case 'uuid':
                return '550e8400-e29b-41d4-a716-446655440000';
            default:
                return null;
        }
    }

    /**
     * @param string $fieldName
     *
     * @return string|null
     */
    protected function generateStringExample(string $fieldName): ?string
    {
        $lowerFieldName = strtolower($fieldName);

        if (str_contains($lowerFieldName, 'email')) {
            return 'user@example.com';
        }

        if (str_contains($lowerFieldName, 'name')) {
            return 'John Doe';
        }

        if (str_contains($lowerFieldName, 'phone')) {
            return '+1-555-123-4567';
        }

        if (str_contains($lowerFieldName, 'address1')) {
            return '123 Main St';
        }

        if (str_contains($lowerFieldName, 'address2')) {
            return 'New York';
        }

        if (str_contains($lowerFieldName, 'address3')) {
            return 'NY 10001';
        }

        return null;
    }
}
