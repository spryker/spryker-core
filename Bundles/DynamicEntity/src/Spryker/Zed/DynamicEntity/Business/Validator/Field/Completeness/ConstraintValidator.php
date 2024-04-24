<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
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
        $indexedConfigurations = $this->getConfigurationsIndexedByTableAliases($dynamicEntityConfigurationTransfer);
        $indexedChildRelations = $this->getChildRelationsIndexedByRelationNames($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $index => $dynamicEntityTransfer) {
            $currentErrorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(), $errorPath);

            $dynamicEntityCollectionResponseTransfer = $this->processValidation(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityTransfer,
                $indexedConfigurations,
                $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
                $currentErrorPath,
            );

            $this->processValidationForChildChains(
                $dynamicEntityTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $indexedConfigurations,
                $indexedChildRelations,
                $currentErrorPath,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $indexedDefinitions
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $indexedChildRelations
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
            foreach ($childRelationTransfer->getDynamicEntities() as $childDynamicEntityTransfer) {
                $dynamicEntityCollectionResponseTransfer = $this->processValidation(
                    $dynamicEntityCollectionResponseTransfer,
                    $childDynamicEntityTransfer,
                    $indexedDefinitions,
                    $indexedChildRelations[$childRelationTransfer->getNameOrFail()]->getNameOrFail(),
                    $errorPath,
                );

                $this->processValidationForChildChains(
                    $childDynamicEntityTransfer,
                    $dynamicEntityCollectionResponseTransfer,
                    $indexedDefinitions,
                    $indexedChildRelations,
                    $errorPath,
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $indexedConfigurations
     * @param string $entityIdentifier
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processValidation(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedConfigurations,
        string $entityIdentifier,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        if (
            !array_key_exists($entityIdentifier, $indexedConfigurations)
            || $indexedConfigurations[$entityIdentifier]->getDynamicEntityDefinition() === null
        ) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        $fieldDefinitionsTransfer = $indexedConfigurations[$entityIdentifier]->getDynamicEntityDefinition()->getFieldDefinitions();

        foreach ($fieldDefinitionsTransfer as $fieldDefinitionTransfer) {
            if (
                $fieldDefinitionTransfer->getValidation() === null
                || $fieldDefinitionTransfer->getValidation()->getConstraints()->count() === 0
            ) {
                continue;
            }

            $this->processFieldValidationConstraints(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityTransfer,
                $fieldDefinitionTransfer,
                $entityIdentifier,
                $errorPath,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processFieldValidationConstraints(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        string $entityIdentifier,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        /** @phpstan-var \Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer $validation */
        $validation = $fieldDefinitionTransfer->getValidation();
        foreach ($validation->getConstraints() as $dynamicEntityFieldValidationConstraintTransfer) {
            $dynamicEntityCollectionResponseTransfer = $this->applyFieldValidationConstraint(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityTransfer,
                $fieldDefinitionTransfer,
                $entityIdentifier,
                $errorPath,
                $dynamicEntityFieldValidationConstraintTransfer,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param string $entityIdentifier
     * @param string $errorPath
     * @param \Generated\Shared\Transfer\DynamicEntityFieldValidationConstraintTransfer $dynamicEntityFieldValidationConstraintTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function applyFieldValidationConstraint(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        string $entityIdentifier,
        string $errorPath,
        DynamicEntityFieldValidationConstraintTransfer $dynamicEntityFieldValidationConstraintTransfer
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($this->fieldsValidationConstraints as $constraint) {
            if (
                $constraint->isApplicable($dynamicEntityFieldValidationConstraintTransfer->getNameOrFail())
                && !$constraint->isValid($dynamicEntityTransfer, $fieldDefinitionTransfer)
            ) {
                $dynamicEntityCollectionResponseTransfer->addError(
                    (new ErrorTransfer())
                        ->setEntityIdentifier($entityIdentifier)
                        ->setMessage($constraint->getErrorMessage())
                        ->setParameters([
                            DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldDefinitionTransfer->getFieldVisibleNameOrFail(),
                            DynamicEntityConfig::ERROR_PATH => $errorPath,
                        ]),
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $indexedChildRelations
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    protected function getChildRelationsIndexedByRelationNames(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildRelations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $indexedChildRelations[$childRelation->getNameOrFail()] = $childRelation;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedChildRelations = $this->getChildRelationsIndexedByRelationNames($childDynamicEntityConfigurationTransfer, $indexedChildRelations);
            }
        }

        return $indexedChildRelations;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function getConfigurationsIndexedByTableAliases(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $indexedConfigurations = [
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail() => $dynamicEntityConfigurationTransfer,
        ];

        return $this->indexChildConfigurationsByTableAliases($dynamicEntityConfigurationTransfer, $indexedConfigurations);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $indexedConfigurations
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function indexChildConfigurationsByTableAliases(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedConfigurations
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $tableAlias = $childDynamicEntityConfigurationTransfer->getTableAliasOrFail();

            if (isset($indexedConfigurations[$tableAlias])) {
                continue;
            }

            $indexedConfigurations[$childDynamicEntityConfigurationTransfer->getTableAliasOrFail()] = $childDynamicEntityConfigurationTransfer;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedConfigurations = $this->indexChildConfigurationsByTableAliases($childDynamicEntityConfigurationTransfer, $indexedConfigurations);
            }
        }

        return $indexedConfigurations;
    }
}
