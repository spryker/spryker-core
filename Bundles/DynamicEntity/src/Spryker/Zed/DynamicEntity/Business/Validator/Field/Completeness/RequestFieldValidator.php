<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

class RequestFieldValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PROVIDED_FIELD_IS_INVALID = 'dynamic_entity.validation.provided_field_is_invalid';

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
        $indexedDefinitions = $this->getDefinitionsIndexedByTableAliases($dynamicEntityConfigurationTransfer);
        $indexedChildRelations = $this->getChildTableAliasesIndexedByRelationNames($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $index => $dynamicEntityTransfer) {
            $currentErrorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(), $errorPath);

            $dynamicEntityCollectionResponseTransfer = $this->validateFieldNames(
                $dynamicEntityTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $indexedDefinitions,
                $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
                $currentErrorPath,
            );

            $dynamicEntityCollectionResponseTransfer = $this->validateRelationChains(
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
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $indexedDefinitions
     * @param array<string, string> $indexedChildRelations
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function validateRelationChains(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $indexedDefinitions,
        array $indexedChildRelations,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelations) {
            foreach ($childRelations->getDynamicEntities() as $index => $dynamicEntityTransfer) {
                $currentErrorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $indexedChildRelations[$childRelations->getName()], $errorPath);

                $dynamicEntityCollectionResponseTransfer = $this->validateFieldNames(
                    $dynamicEntityTransfer,
                    $dynamicEntityCollectionResponseTransfer,
                    $indexedDefinitions,
                    $indexedChildRelations[$childRelations->getName()],
                    $currentErrorPath,
                );

                if ($dynamicEntityTransfer->getChildRelations()->count() > 0) {
                    $dynamicEntityCollectionResponseTransfer = $this->validateRelationChains(
                        $dynamicEntityTransfer,
                        $dynamicEntityCollectionResponseTransfer,
                        $indexedDefinitions,
                        $indexedChildRelations,
                        $currentErrorPath,
                    );
                }
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, string> $indexedChildRelations
     *
     * @return array<string, string>
     */
    protected function getChildTableAliasesIndexedByRelationNames(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildRelations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfiguration = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $indexedChildRelations[$childRelation->getNameOrFail()] = $childDynamicEntityConfiguration->getTableAliasOrFail();

            if ($childDynamicEntityConfiguration->getChildRelations()->count() > 0) {
                $indexedChildRelations = $this->getChildTableAliasesIndexedByRelationNames($childDynamicEntityConfiguration, $indexedChildRelations);
            }
        }

        return $indexedChildRelations;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer>
     */
    protected function getDefinitionsIndexedByTableAliases(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $indexedDefinitions = [];

        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $dynamicEntityDefinitionField) {
            $indexedDefinitions[$dynamicEntityConfigurationTransfer->getTableAliasOrFail()][$dynamicEntityDefinitionField->getFieldVisibleNameOrFail()] = $dynamicEntityDefinitionField;
        }

        return $this->getChildDefinitionsIndexedByTableAliases($dynamicEntityConfigurationTransfer, $indexedDefinitions);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<mixed> $indexedDefinitions
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer>
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

            foreach ($childDynamicEntityConfiguration->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $dynamicEntityDefinitionField) {
                $indexedDefinitions[$tableAlias][$dynamicEntityDefinitionField->getFieldVisibleNameOrFail()] = $dynamicEntityDefinitionField;
            }

            if ($childDynamicEntityConfiguration->getChildRelations()->count() > 0) {
                $indexedDefinitions = $this->getChildDefinitionsIndexedByTableAliases($childDynamicEntityConfiguration, $indexedDefinitions);
            }
        }

        return $indexedDefinitions;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $indexedDefinitions
     * @param string $entityIdentifier
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function validateFieldNames(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $indexedDefinitions,
        string $entityIdentifier,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityTransfer->getFields() as $fieldName => $fieldValue) {
            if (is_array($fieldValue)) {
                continue;
            }

            if (isset($indexedDefinitions[$entityIdentifier][$fieldName]) || $fieldName === static::IDENTIFIER) {
                continue;
            }

            $dynamicEntityCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($entityIdentifier)
                    ->setMessage(static::GLOSSARY_KEY_PROVIDED_FIELD_IS_INVALID)
                    ->setParameters([
                        DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldName,
                        DynamicEntityConfig::ERROR_PATH => $errorPath,
                    ]),
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }
}
