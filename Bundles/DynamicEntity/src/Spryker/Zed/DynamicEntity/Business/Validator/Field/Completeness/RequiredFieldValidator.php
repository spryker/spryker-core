<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface;
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
     * @var \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface
     */
    protected DynamicEntityIndexerInterface $dynamicEntityIndexer;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver
     * @param \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface $dynamicEntityIndexer
     */
    public function __construct(
        DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver,
        DynamicEntityIndexerInterface $dynamicEntityIndexer
    ) {
        $this->dynamicEntityErrorPathResolver = $dynamicEntityErrorPathResolver;
        $this->dynamicEntityIndexer = $dynamicEntityIndexer;
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
        if ((bool)$dynamicEntityCollectionRequestTransfer->getIsCreatable() === false) {
            return [];
        }
        $errorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $dynamicEntityConfigurationTransfer->getTableAliasOrFail());

        $errorTransfers = $this->processValidation(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
            $errorPath,
        );

        return array_merge($errorTransfers, $this->processValidationForChildChains(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $errorPath,
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $entityIdentifier
     * @param string $errorPath
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function processValidation(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $entityIdentifier,
        string $errorPath
    ): array {
        $errorTransfers = [];
        $dynamicEntityDefinitionTransfer = $this->dynamicEntityIndexer->getConfigurationsIndexedByTableAlias($dynamicEntityConfigurationTransfer)[$entityIdentifier]->getDynamicEntityDefinitionOrFail();
        $childDynamicEntityConfigurationsIndexedByTableAliases = $this->dynamicEntityIndexer->getChildRelationsIndexedByTableAlias($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            if ($fieldDefinitionTransfer->getValidation() === null) {
                continue;
            }

            if ($fieldDefinitionTransfer->getValidation()->getIsRequired() === false) {
                continue;
            }

            if ($this->isFieldForeignKey($childDynamicEntityConfigurationsIndexedByTableAliases, $fieldDefinitionTransfer, $entityIdentifier) === true) {
                continue;
            }

            if (isset($dynamicEntityTransfer->getFields()[$fieldDefinitionTransfer->getFieldVisibleName()])) {
                continue;
            }

            $errorTransfers[] = (new ErrorTransfer())
                ->setEntityIdentifier($entityIdentifier)
                ->setMessage(static::GLOSSARY_KEY_REQUIRED_FIELD_IS_MISSING)
                ->setParameters([
                    DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldDefinitionTransfer->getFieldVisibleNameOrFail(),
                    DynamicEntityConfig::ERROR_PATH => $errorPath,
                ]);
        }

        return $errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $errorPath
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function processValidationForChildChains(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath
    ): array {
        $errorTransfers = [];
        $childRelationsIndexedByRelationNames = $this->dynamicEntityIndexer->getChildRelationsIndexedByRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelationTransfer) {
            foreach ($childRelationTransfer->getDynamicEntities() as $index => $childDynamicEntityTransfer) {
                $childTableAlias = $childRelationsIndexedByRelationNames[$childRelationTransfer->getNameOrFail()]->getChildDynamicEntityConfigurationOrFail()->getTableAliasOrFail();
                $childErrorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $childTableAlias, $errorPath);

                $validatedErrorTransfers = $this->processValidation(
                    $childDynamicEntityTransfer,
                    $dynamicEntityConfigurationTransfer,
                    $childTableAlias,
                    $childErrorPath,
                );

                $childValidatedErrorTransfers = $this->processValidationForChildChains(
                    $childDynamicEntityTransfer,
                    $dynamicEntityConfigurationTransfer,
                    $childErrorPath,
                );

                $errorTransfers = array_merge($errorTransfers, $validatedErrorTransfers, $childValidatedErrorTransfers);
            }
        }

        return $errorTransfers;
    }

    /**
     * @param array<mixed> $childDynamicEntityConfigurationsIndexedByTableAliases
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param string $entityIdentifier
     *
     * @return bool
     */
    protected function isFieldForeignKey(
        array $childDynamicEntityConfigurationsIndexedByTableAliases,
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        string $entityIdentifier
    ): bool {
        if (isset($childDynamicEntityConfigurationsIndexedByTableAliases[$entityIdentifier]) === false) {
            return false;
        }

        foreach ($childDynamicEntityConfigurationsIndexedByTableAliases[$entityIdentifier]->getRelationFieldMappings() as $relationFieldMappingTransfer) {
            if ($fieldDefinitionTransfer->getFieldName() === $relationFieldMappingTransfer->getChildFieldName()) {
                continue;
            }

            return false;
        }

        return true;
    }
}
