<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

class RequiredFieldValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REQUIRED_FIELD_IS_MISSING = 'dynamic_entity.validation.required_field_is_missing';

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface
     */
    protected DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver
     */
    public function __construct(DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver)
    {
        $this->dynamicEntityErrorPathResolver = $dynamicEntityErrorPathResolver;
    }

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
        if ((bool)$dynamicEntityCollectionRequestTransfer->getIsCreatable() === false) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        $indexedDefinitions = $this->getDefinitionsIndexedByTableAlias($dynamicEntityConfigurationTransfer);
        $indexedChildRelations = $this->getChildTableAliasesIndexByRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $index => $dynamicEntityTransfer) {
            $currentErrorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(), $errorPath);

            $dynamicEntityCollectionResponseTransfer = $this->processValidation(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityTransfer,
                $indexedDefinitions,
                $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
                $currentErrorPath,
            );

            $this->processValidationForChildChains(
                $dynamicEntityTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $indexedDefinitions,
                $indexedChildRelations,
                $currentErrorPath,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<mixed> $indexedDefinitions
     * @param array<string, string> $indexedChildRelations
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processValidationForChildChains(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $indexedDefinitions,
        array $indexedChildRelations,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelationTransfer) {
            foreach ($childRelationTransfer->getDynamicEntities() as $index => $childDynamicEntityTransfer) {
                $currentErrorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $indexedChildRelations[$childRelationTransfer->getNameOrFail()], $errorPath);

                $dynamicEntityCollectionResponseTransfer = $this->processValidation(
                    $dynamicEntityCollectionResponseTransfer,
                    $childDynamicEntityTransfer,
                    $indexedDefinitions,
                    $indexedChildRelations[$childRelationTransfer->getNameOrFail()],
                    $currentErrorPath,
                );

                $this->processValidationForChildChains(
                    $childDynamicEntityTransfer,
                    $dynamicEntityCollectionResponseTransfer,
                    $indexedDefinitions,
                    $indexedChildRelations,
                    $currentErrorPath,
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<mixed> $indexedDefinitions
     * @param string $entityIdentifier
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processValidation(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedDefinitions,
        string $entityIdentifier,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        $fieldDefinitionsTransfer = $indexedDefinitions[$entityIdentifier][DynamicEntityConfigurationTransfer::DYNAMIC_ENTITY_DEFINITION]->getFieldDefinitions();

        foreach ($fieldDefinitionsTransfer as $fieldDefinitionTransfer) {
            if ($fieldDefinitionTransfer->getValidation() === null) {
                continue;
            }

            if ($fieldDefinitionTransfer->getValidation()->getIsRequired() === false) {
                continue;
            }

            if ($this->isFieldForeignKey($indexedDefinitions[$entityIdentifier], $fieldDefinitionTransfer) === true) {
                continue;
            }

            if (isset($dynamicEntityTransfer->getFields()[$fieldDefinitionTransfer->getFieldVisibleName()])) {
                continue;
            }

            $dynamicEntityCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($entityIdentifier)
                    ->setMessage(static::GLOSSARY_KEY_REQUIRED_FIELD_IS_MISSING)
                    ->setParameters([
                        DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldDefinitionTransfer->getFieldVisibleNameOrFail(),
                        DynamicEntityConfig::ERROR_PATH => $errorPath,
                    ]),
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param array<mixed> $indexedRelationsFieldMappings
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     *
     * @return bool
     */
    protected function isFieldForeignKey(
        array $indexedRelationsFieldMappings,
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
    ): bool {
        if (!isset($indexedRelationsFieldMappings[DynamicEntityConfigurationRelationTransfer::RELATION_FIELD_MAPPINGS])) {
            return false;
        }

        foreach ($indexedRelationsFieldMappings[DynamicEntityConfigurationRelationTransfer::RELATION_FIELD_MAPPINGS] as $relationFieldMappingTransfer) {
            if ($fieldDefinitionTransfer->getFieldName() === $relationFieldMappingTransfer->getChildFieldName()) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, string> $indexedChildRelations
     *
     * @return array<string, string>
     */
    protected function getChildTableAliasesIndexByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildRelations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $indexedChildRelations[$childRelation->getNameOrFail()] = $childDynamicEntityConfigurationTransfer->getTableAliasOrFail();

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedChildRelations = $this->getChildTableAliasesIndexByRelationName($childDynamicEntityConfigurationTransfer, $indexedChildRelations);
            }
        }

        return $indexedChildRelations;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function getDefinitionsIndexedByTableAlias(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $indexedDefinitions = [
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail() => [
                DynamicEntityConfigurationTransfer::DYNAMIC_ENTITY_DEFINITION => $dynamicEntityConfigurationTransfer->getDynamicEntityDefinition(),
            ],
        ];

        return $this->getChildRelationsIndexedByTableAlias($dynamicEntityConfigurationTransfer, $indexedDefinitions);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<mixed> $indexedDefinitions
     *
     * @return array<mixed>
     */
    protected function getChildRelationsIndexedByTableAlias(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedDefinitions
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $tableAlias = $childDynamicEntityConfigurationTransfer->getTableAliasOrFail();

            if (isset($indexedDefinitions[$tableAlias])) {
                continue;
            }

            $indexedDefinitions[$tableAlias] = [
                DynamicEntityConfigurationTransfer::DYNAMIC_ENTITY_DEFINITION => $childDynamicEntityConfigurationTransfer->getDynamicEntityDefinition(),
                DynamicEntityConfigurationRelationTransfer::RELATION_FIELD_MAPPINGS => $childRelation->getRelationFieldMappings(),
            ];

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedDefinitions = $this->getChildRelationsIndexedByTableAlias($childDynamicEntityConfigurationTransfer, $indexedDefinitions);
            }
        }

        return $indexedDefinitions;
    }
}
