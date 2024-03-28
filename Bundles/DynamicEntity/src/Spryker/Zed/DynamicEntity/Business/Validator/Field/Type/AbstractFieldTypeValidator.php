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
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

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
    protected const PLACEHOLDER_TABLE_ALIAS = '%tableAlias%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_VALIDATION_RULES = '%validationRules%';

    /**
     * @var string
     */
    protected const FORMATTED_INDEX_PLACEHOLDER = '%s[%d]';

    /**
     * @var string
     */
    protected const RELATION_CHAIN_PLACEHOLDER = '%s.%s';

    /**
     * @var string
     */
    protected const CHAIN_DELIMITER = '.';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param string|null $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function validate(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        ?string $errorPath = null
    ): DynamicEntityCollectionResponseTransfer {
        $indexedDefinitions = $this->getDefinitionsIndexedByTableAliases($dynamicEntityConfigurationTransfer);
        $indexedChildRelations = $this->getTableAliasesIndexedByChildRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $index => $dynamicEntityTransfer) {
            $currentErrorPath = $this->getErrorPath($index, $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(), $errorPath);

            $this->processValidation(
                $dynamicEntityTransfer,
                $indexedDefinitions,
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
                $currentErrorPath,
            );

            $this->validateChildRelationChainFieldTypes(
                $dynamicEntityTransfer,
                $indexedDefinitions,
                $dynamicEntityCollectionResponseTransfer,
                $indexedChildRelations,
                $currentErrorPath,
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
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processValidation(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedDefinitions,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        string $entityIdentifier,
        string $errorPath
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
                            DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldName,
                            DynamicEntityConfig::ERROR_PATH => $errorPath,
                    ]),
                );
            }

            if ($this->isValidValue($fieldValue, $fieldDefinitionTransfer) === false) {
                $dynamicEntityCollectionResponseTransfer->addError(
                    $this->buildValueErrorTransfer(
                        $entityIdentifier,
                        $fieldDefinitionTransfer->getValidationOrFail()->toArray(),
                        $fieldName,
                        $errorPath,
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
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function validateChildRelationChainFieldTypes(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedDefinitions,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $indexedChildRelations,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelationTransfer) {
            foreach ($childRelationTransfer->getDynamicEntities() as $index => $childDynamicEntityTransfer) {
                $currentErrorPath = $this->getErrorPath($index, $indexedChildRelations[$childRelationTransfer->getNameOrFail()], $errorPath);

                $dynamicEntityCollectionResponseTransfer = $this->processValidation(
                    $childDynamicEntityTransfer,
                    $indexedDefinitions,
                    $dynamicEntityCollectionResponseTransfer,
                    $indexedChildRelations[$childRelationTransfer->getNameOrFail()],
                    $currentErrorPath,
                );

                $this->validateChildRelationChainFieldTypes(
                    $childDynamicEntityTransfer,
                    $indexedDefinitions,
                    $dynamicEntityCollectionResponseTransfer,
                    $indexedChildRelations,
                    $currentErrorPath,
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
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function buildValueErrorTransfer(
        string $entityIdentifier,
        array $validationRules,
        string $fieldName,
        string $errorPath
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
                DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldName,
                DynamicEntityConfig::ERROR_PATH => $errorPath,
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

    /**
     * @param int $index
     * @param string $tableAlias
     * @param string|null $parentErrorPath
     *
     * @return string
     */
    protected function getErrorPath(int $index, string $tableAlias, ?string $parentErrorPath): string
    {
        $formattedIndex = sprintf(static::FORMATTED_INDEX_PLACEHOLDER, $tableAlias, $index);

        if ($parentErrorPath === $formattedIndex) {
            return $parentErrorPath;
        }

        return ltrim(sprintf(static::RELATION_CHAIN_PLACEHOLDER, $parentErrorPath, $formattedIndex), static::CHAIN_DELIMITER);
    }
}
