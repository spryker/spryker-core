<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Type;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

abstract class AbstractFieldTypeValidator
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_INVALID_FIELD_TYPE = 'dynamic_entity.validation.invalid_field_type';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_INVALID_FIELD_VALUE = 'dynamic_entity.validation.invalid_field_value';

    /**
     * @var string
     */
    protected const PLACEHOLDER_FIELD_NAME = '%fieldName%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_TABLE_ALIAS = '%tableAlias%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_VALIDATION_RULES = '%validationRules%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ROW_NUMBER = '%rowNumber%';

    /**
     * @var int
     */
    protected int $rowNumber = 1;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function validate(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $indexedDefinitions = $this->getDefinitionsIndexedByTableAliases($dynamicEntityConfigurationTransfer);
        $indexedChildRelations = $this->getTableAliasesIndexedByChildRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $this->processValidation(
                $dynamicEntityTransfer,
                $indexedDefinitions,
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
            );

            $this->rowNumber++;

            $this->validateChildRelationChainFieldTypes(
                $dynamicEntityTransfer,
                $indexedDefinitions,
                $dynamicEntityCollectionResponseTransfer,
                $indexedChildRelations,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param mixed $fieldValue
     *
     * @return bool
     */
    abstract public function isValidType(mixed $fieldValue): bool;

    /**
     * @param mixed $fieldValue
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return bool
     */
    abstract public function isValidValue(mixed $fieldValue, DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): bool;

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<mixed> $indexedDefinitions
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param string $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processValidation(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedDefinitions,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        string $entityIdentifier
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($indexedDefinitions[$entityIdentifier]->getFieldDefinitions() as $fieldDefinitionTransfer) {
            $fieldName = $fieldDefinitionTransfer->getFieldVisibleNameOrFail();

            if (!isset($dynamicEntityTransfer->getFields()[$fieldName]) || !$this->isSupportedType($fieldDefinitionTransfer->getTypeOrFail())) {
                continue;
            }

            $fieldValue = $dynamicEntityTransfer->getFields()[$fieldName];

            if ($this->isValidType($fieldValue) === false) {
                $dynamicEntityCollectionResponseTransfer->addError(
                    (new ErrorTransfer())
                        ->setEntityIdentifier($entityIdentifier)
                        ->setMessage(static::GLOSSARY_KEY_ERROR_INVALID_FIELD_TYPE)
                        ->setParameters([
                            static::PLACEHOLDER_FIELD_NAME => $fieldName,
                            static::PLACEHOLDER_TABLE_ALIAS => $entityIdentifier,
                    ]),
                );
            }

            if ($this->isValidValue($fieldValue, $fieldDefinitionTransfer) === false) {
                $dynamicEntityCollectionResponseTransfer->addError(
                    $this->buildValueErrorTransfer(
                        $entityIdentifier,
                        $fieldDefinitionTransfer->getValidationOrFail()->toArray(),
                        $fieldName,
                    ),
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<mixed> $indexedDefinitions
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, string> $indexedChildRelations
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function validateChildRelationChainFieldTypes(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedDefinitions,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $indexedChildRelations
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelationTransfer) {
            foreach ($childRelationTransfer->getDynamicEntities() as $childDynamicEntityTransfer) {
                $dynamicEntityCollectionResponseTransfer = $this->processValidation(
                    $childDynamicEntityTransfer,
                    $indexedDefinitions,
                    $dynamicEntityCollectionResponseTransfer,
                    $indexedChildRelations[$childRelationTransfer->getNameOrFail()],
                );

                $this->rowNumber++;

                $this->validateChildRelationChainFieldTypes(
                    $childDynamicEntityTransfer,
                    $indexedDefinitions,
                    $dynamicEntityCollectionResponseTransfer,
                    $indexedChildRelations,
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isSupportedType(string $type): bool
    {
        return $type === $this->getType();
    }

    /**
     * @param string $entityIdentifier
     * @param array<string, string> $validationRules
     * @param string $fieldName
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function buildValueErrorTransfer(
        string $entityIdentifier,
        array $validationRules,
        string $fieldName
    ): ErrorTransfer {
        $rules = [];
        foreach ($validationRules as $key => $value) {
            if ($value !== null) {
                $rules[] = sprintf('%s: %s', $key, $value);
            }
        }

        return (new ErrorTransfer())
            ->setEntityIdentifier($entityIdentifier)
            ->setMessage(static::GLOSSARY_KEY_ERROR_INVALID_FIELD_VALUE)
            ->setParameters([
                static::PLACEHOLDER_VALIDATION_RULES => implode(', ', $rules),
                static::PLACEHOLDER_FIELD_NAME => $fieldName,
                static::PLACEHOLDER_ROW_NUMBER => $this->rowNumber,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, string> $indexedChildRelations
     *
     * @return array<string, string>
     */
    protected function getTableAliasesIndexedByChildRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildRelations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfiguration = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $indexedChildRelations[$childRelation->getNameOrFail()] = $childDynamicEntityConfiguration->getTableAliasOrFail();

            if ($childDynamicEntityConfiguration->getChildRelations()->count() > 0) {
                $indexedChildRelations = $this->getTableAliasesIndexedByChildRelationName($childDynamicEntityConfiguration, $indexedChildRelations);
            }
        }

        return $indexedChildRelations;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer>
     */
    protected function getDefinitionsIndexedByTableAliases(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $indexedDefinitions = [
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail() => $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
        ];

        return $this->getChildDefinitionsIndexedByTableAliases($dynamicEntityConfigurationTransfer, $indexedDefinitions);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer> $indexedDefinitions
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer>
     */
    protected function getChildDefinitionsIndexedByTableAliases(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedDefinitions
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfiguration = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $tableAlias = $childDynamicEntityConfiguration->getTableAliasOrFail();

            if (isset($indexedDefinitions[$tableAlias])) {
                continue;
            }

            $indexedDefinitions[$tableAlias] = $childDynamicEntityConfiguration->getDynamicEntityDefinitionOrFail();

            if ($childDynamicEntityConfiguration->getChildRelations()->count() > 0) {
                $indexedDefinitions = $this->getChildDefinitionsIndexedByTableAliases($childDynamicEntityConfiguration, $indexedDefinitions);
            }
        }

        return $indexedDefinitions;
    }
}
