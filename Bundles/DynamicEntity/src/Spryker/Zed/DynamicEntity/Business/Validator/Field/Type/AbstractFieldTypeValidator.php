<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Type;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
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
    protected const PLACEHOLDER_VALIDATION_RULES = '%validationRules%';

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface
     */
    protected DynamicEntityIndexerInterface $dynamicEntityIndexer;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface
     */
    protected DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface $dynamicEntityIndexer
     * @param \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver
     */
    public function __construct(
        DynamicEntityIndexerInterface $dynamicEntityIndexer,
        DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver
    ) {
        $this->dynamicEntityIndexer = $dynamicEntityIndexer;
        $this->dynamicEntityErrorPathResolver = $dynamicEntityErrorPathResolver;
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
        $errorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $dynamicEntityConfigurationTransfer->getTableAliasOrFail());

        $errorTransfers = $this->processValidation(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
            $errorPath,
        );

        return array_merge($errorTransfers, $this->validateChildRelationChainFieldTypes(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $errorPath,
        ));
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

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            $fieldName = $fieldDefinitionTransfer->getFieldVisibleNameOrFail();

            if (!isset($dynamicEntityTransfer->getFields()[$fieldName]) || !$this->isSupportedType($fieldDefinitionTransfer->getTypeOrFail())) {
                continue;
            }

            $fieldValue = $dynamicEntityTransfer->getFields()[$fieldName];

            if ($this->isValidType($fieldValue) === false) {
                $errorTransfers[] = (new ErrorTransfer())
                    ->setEntityIdentifier($entityIdentifier)
                    ->setMessage(static::GLOSSARY_KEY_ERROR_INVALID_FIELD_TYPE)
                    ->setParameters([
                        DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldName,
                        DynamicEntityConfig::ERROR_PATH => $errorPath,
                    ]);
            }

            if ($this->isValidValue($fieldValue, $fieldDefinitionTransfer) === false) {
                $errorTransfers[] = $this->buildValueErrorTransfer(
                    $entityIdentifier,
                    $fieldDefinitionTransfer->getValidationOrFail()->toArray(),
                    $fieldName,
                    $errorPath,
                );
            }
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
    protected function validateChildRelationChainFieldTypes(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath
    ): array {
        $errorTransfers = [];
        $indexedChildRelations = $this->dynamicEntityIndexer->getChildRelationsIndexedByRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelationTransfer) {
            foreach ($childRelationTransfer->getDynamicEntities() as $index => $childDynamicEntityTransfer) {
                $childTableAlias = $indexedChildRelations[$childRelationTransfer->getNameOrFail()]->getChildDynamicEntityConfigurationOrFail()->getTableAliasOrFail();
                $childErrorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $childTableAlias, $errorPath);

                $validatedErrorTransfers = $this->processValidation(
                    $childDynamicEntityTransfer,
                    $dynamicEntityConfigurationTransfer,
                    $indexedChildRelations[$childRelationTransfer->getNameOrFail()]->getChildDynamicEntityConfigurationOrFail()->getTableAliasOrFail(),
                    $childErrorPath,
                );

                $validatedChildErrorTransfers = $this->validateChildRelationChainFieldTypes(
                    $childDynamicEntityTransfer,
                    $dynamicEntityConfigurationTransfer,
                    $childErrorPath,
                );

                $errorTransfers = array_merge($errorTransfers, $validatedErrorTransfers, $validatedChildErrorTransfers);
            }
        }

        return $errorTransfers;
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
     * @param array<string, mixed> $validationRules
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
            if (is_array($value) && count($value) === 0) {
                continue;
            }

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
}
