<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Immutability;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

abstract class AbstractFieldImmutabilityValidator
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED = 'dynamic_entity.validation.modification_of_immutable_field_prohibited';

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
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param array<mixed> $dynamicEntityFields
     * @param string $identifier
     *
     * @return bool
     */
    abstract public function isFieldNonModifiable(
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        array $dynamicEntityFields,
        string $identifier
    ): bool;

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

        $errorTransfers = $this->executeValidation(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
            $errorPath,
        );

        return array_merge($errorTransfers, $this->validateChildDynamicEntities(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $errorPath,
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $tableAlias
     * @param string $errorPath
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function executeValidation(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $tableAlias,
        string $errorPath
    ): array {
        $errorTransfers = [];
        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail();
        $identifier = $this->getIdentifierVisibleName(
            $dynamicEntityDefinitionTransfer->getIdentifierOrFail(),
            $dynamicEntityConfigurationTransfer,
        );

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            if ($this->isFieldNonModifiable($fieldDefinitionTransfer, $dynamicEntityTransfer->getFields(), $identifier) === true) {
                $errorTransfers[] = (new ErrorTransfer())
                    ->setEntityIdentifier($tableAlias)
                    ->setMessage(static::GLOSSARY_KEY_ERROR_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED)
                    ->setParameters([
                        DynamicEntityConfig::PLACEHOLDER_FIELD_NAME => $fieldDefinitionTransfer->getFieldVisibleNameOrFail(),
                        DynamicEntityConfig::ERROR_PATH => $errorPath,
                    ]);
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
    protected function validateChildDynamicEntities(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath
    ): array {
        $errorTransfers = [];
        $childRelationsIndexedByRelationName = $this->dynamicEntityIndexer->getChildRelationsIndexedByRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelationTransfer) {
            $errorTransfers = array_merge(
                $errorTransfers,
                $this->validateChildRelationEntities(
                    $childRelationTransfer,
                    $dynamicEntityConfigurationTransfer,
                    $childRelationsIndexedByRelationName,
                    $errorPath,
                ),
            );
        }

        return $errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityRelationTransfer $childRelationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<mixed> $childRelationsIndexedByRelationName
     * @param string $errorPath
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function validateChildRelationEntities(
        DynamicEntityRelationTransfer $childRelationTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $childRelationsIndexedByRelationName,
        string $errorPath
    ): array {
        $errorTransfers = [];
        foreach ($childRelationTransfer->getDynamicEntities() as $index => $childDynamicEntityTransfer) {
            $childTableAlias = $childRelationsIndexedByRelationName[$childRelationTransfer->getNameOrFail()]->getChildDynamicEntityConfigurationOrFail()->getTableAliasOrFail();
            $childErrorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $childTableAlias, $errorPath);

            $immutableErrorTransfers = $this->executeValidation(
                $childDynamicEntityTransfer,
                $dynamicEntityConfigurationTransfer,
                $childTableAlias,
                $childErrorPath,
            );

            $immutableChildErrorTransfers = $this->validateChildDynamicEntities(
                $childDynamicEntityTransfer,
                $dynamicEntityConfigurationTransfer,
                $childErrorPath,
            );

            $errorTransfers = array_merge($errorTransfers, $immutableErrorTransfers, $immutableChildErrorTransfers);
        }

        return $errorTransfers;
    }

    /**
     * @param string $identifier
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getIdentifierVisibleName(string $identifier, DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $fieldDefinitionTransfer) {
            if ($fieldDefinitionTransfer->getFieldNameOrFail() === $identifier) {
                return $fieldDefinitionTransfer->getFieldVisibleNameOrFail();
            }
        }

        return $identifier;
    }
}
