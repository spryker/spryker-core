<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationConstraintTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

class ConstraintValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface
     */
    protected DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver;

    /**
     * @var array<\Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\Constraint\ConstraintInterface>
     */
    protected array $fieldsValidationConstraints;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver
     * @param array<\Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\Constraint\ConstraintInterface> $fieldsValidationConstraints
     */
    public function __construct(
        DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver,
        array $fieldsValidationConstraints
    ) {
        $this->dynamicEntityErrorPathResolver = $dynamicEntityErrorPathResolver;
        $this->fieldsValidationConstraints = $fieldsValidationConstraints;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param int $index
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validate(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        int $index
    ): array {
        $errorPath = $this->dynamicEntityErrorPathResolver->getErrorPath(
            $index,
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
        );

        $configurationsIndexedByTableAlias = $this->getConfigurationsIndexedByTableAlias($dynamicEntityConfigurationTransfer);
        $childRelationsIndexedByRelationName = $this->getChildRelationsIndexedByRelationName($dynamicEntityConfigurationTransfer);

        $errorTransfers = $this->processValidation(
            $dynamicEntityTransfer,
            $configurationsIndexedByTableAlias,
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
            $errorPath,
        );

        $errorTransfers = array_merge($errorTransfers, $this->processValidationForChildChains(
            $dynamicEntityTransfer,
            $configurationsIndexedByTableAlias,
            $childRelationsIndexedByRelationName,
            $errorPath,
        ));

        return $errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $configurationsIndexedByTableAlias
     * @param string $entityIdentifier
     * @param string $errorPath
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function processValidation(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $configurationsIndexedByTableAlias,
        string $entityIdentifier,
        string $errorPath
    ): array {
        if (
            !array_key_exists($entityIdentifier, $configurationsIndexedByTableAlias)
            || $configurationsIndexedByTableAlias[$entityIdentifier]->getDynamicEntityDefinition() === null
        ) {
            return [];
        }

        $fieldDefinitionsTransfer = $configurationsIndexedByTableAlias[$entityIdentifier]->getDynamicEntityDefinition()->getFieldDefinitions();
        $errorTransfers = [];
        foreach ($fieldDefinitionsTransfer as $fieldDefinitionTransfer) {
            if (
                $fieldDefinitionTransfer->getValidation() === null
                || $fieldDefinitionTransfer->getValidation()->getConstraints()->count() === 0
            ) {
                continue;
            }

            $errorTransfers = array_merge($errorTransfers, $this->processFieldValidationConstraints(
                $dynamicEntityTransfer,
                $fieldDefinitionTransfer,
                $entityIdentifier,
                $errorPath,
            ));
        }

        return $errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $configurationsIndexedByTableAlias
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $childRelationsIndexedByRelationName
     * @param string $errorPath
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function processValidationForChildChains(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $configurationsIndexedByTableAlias,
        array $childRelationsIndexedByRelationName,
        string $errorPath
    ): array {
        $errorTransfers = [];
        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelationTransfer) {
            foreach ($childRelationTransfer->getDynamicEntities() as $childDynamicEntityTransfer) {
                $errorTransfers = array_merge($errorTransfers, $this->processValidation(
                    $childDynamicEntityTransfer,
                    $configurationsIndexedByTableAlias,
                    $childRelationsIndexedByRelationName[$childRelationTransfer->getNameOrFail()]->getNameOrFail(),
                    $errorPath,
                ));

                $errorTransfers = array_merge($errorTransfers, $this->processValidationForChildChains(
                    $childDynamicEntityTransfer,
                    $configurationsIndexedByTableAlias,
                    $childRelationsIndexedByRelationName,
                    $errorPath,
                ));
            }
        }

        return $errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param string $errorPath
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function processFieldValidationConstraints(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        string $entityIdentifier,
        string $errorPath
    ): array {
        $errorTransfers = [];
        /** @phpstan-var \Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer $fieldValidationTransfer */
        $fieldValidationTransfer = $fieldDefinitionTransfer->getValidation();
        foreach ($fieldValidationTransfer->getConstraints() as $dynamicEntityFieldValidationConstraintTransfer) {
            $errorTransfers = array_merge($errorTransfers, $this->applyFieldValidationConstraint(
                $dynamicEntityTransfer,
                $fieldDefinitionTransfer,
                $entityIdentifier,
                $errorPath,
                $dynamicEntityFieldValidationConstraintTransfer,
            ));
        }

        return $errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param string $errorPath
     * @param \Generated\Shared\Transfer\DynamicEntityFieldValidationConstraintTransfer $dynamicEntityFieldValidationConstraintTransfer
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function applyFieldValidationConstraint(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        string $entityIdentifier,
        string $errorPath,
        DynamicEntityFieldValidationConstraintTransfer $dynamicEntityFieldValidationConstraintTransfer
    ): array {
        $errorTransfers = [];
        foreach ($this->fieldsValidationConstraints as $constraint) {
            if (
                $constraint->isApplicable($dynamicEntityFieldValidationConstraintTransfer->getNameOrFail())
                && !$constraint->isValid($dynamicEntityTransfer, $fieldDefinitionTransfer)
            ) {
                $errorTransfers[] = (new ErrorTransfer())
                    ->setEntityIdentifier($entityIdentifier)
                    ->setMessage($constraint->getErrorMessage())
                    ->setParameters([
                        DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldDefinitionTransfer->getFieldVisibleNameOrFail(),
                        DynamicEntityConfig::ERROR_PATH => $errorPath,
                    ]);
            }
        }

        return $errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $childRelationsIndexedByRelationName
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    protected function getChildRelationsIndexedByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $childRelationsIndexedByRelationName = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $childRelationsIndexedByRelationName[$childRelation->getNameOrFail()] = $childRelation;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $childRelationsIndexedByRelationName = $this->getChildRelationsIndexedByRelationName($childDynamicEntityConfigurationTransfer, $childRelationsIndexedByRelationName);
            }
        }

        return $childRelationsIndexedByRelationName;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function getConfigurationsIndexedByTableAlias(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $configurationsIndexedByTableAlias = [
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail() => $dynamicEntityConfigurationTransfer,
        ];

        return $this->indexChildConfigurationsByTableAliases($dynamicEntityConfigurationTransfer, $configurationsIndexedByTableAlias);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $configurationsIndexedByTableAlias
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function indexChildConfigurationsByTableAliases(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $configurationsIndexedByTableAlias
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $tableAlias = $childDynamicEntityConfigurationTransfer->getTableAliasOrFail();

            if (isset($configurationsIndexedByTableAlias[$tableAlias])) {
                continue;
            }

            $configurationsIndexedByTableAlias[$childDynamicEntityConfigurationTransfer->getTableAliasOrFail()] = $childDynamicEntityConfigurationTransfer;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $configurationsIndexedByTableAlias = $this->indexChildConfigurationsByTableAliases($childDynamicEntityConfigurationTransfer, $configurationsIndexedByTableAlias);
            }
        }

        return $configurationsIndexedByTableAlias;
    }
}
