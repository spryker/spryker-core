<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Builder;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

class DynamicEntityCollectionRequestBuilder implements DynamicEntityCollectionRequestBuilderInterface
{
    /**
     * @var string
     */
    protected const FORMAT_RELATION_CHAIN_ELEMENT = '%s%s.';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return array<string>
     */
    public function buildRelationChainsFromDynamicEntityCollectionRequest(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): array {
        $allRelationChains = [];
        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $relationChains = $this->processEntityKeys($dynamicEntityTransfer->getFields(), []);

            foreach ($relationChains as $relationChain) {
                $allRelationChains = array_merge(
                    $allRelationChains,
                    $this->processRelationChain([], $relationChain),
                );
            }
        }

        return array_unique($allRelationChains);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer>
     */
    public function buildDynamicEntityCollectionRequestTransfersArrayIndexedByTableAlias(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): array {
        $configurationsIndexedByChildRelationName = $this->getConfigurationsIndexedByChildRelationName($dynamicEntityConfigurationCollectionTransfer);

        $mainTableAlias = $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail();
        $flattenFieldsByAlias = [
            $mainTableAlias => [],
        ];
        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $flattenFieldsByAlias[$mainTableAlias][] = $this->getNonRelationalFields($dynamicEntityTransfer->getFields());

            $relationalFields = $this->getRelationalFields($dynamicEntityTransfer->getFields());
            $flattenFieldsByAlias = $this->flattenEntityRelations($configurationsIndexedByChildRelationName, $relationalFields, $flattenFieldsByAlias);
        }

        $dynamicEntityCollectionRequestTransfersByTableAlias = [];
        foreach ($flattenFieldsByAlias as $tableAlias => $entityFieldsCollection) {
            $dynamicEntityCollectionRequestTransfersByTableAlias[$tableAlias] = $this->createDynamicEntityCollectionRequestTransfer($tableAlias, $entityFieldsCollection, $dynamicEntityCollectionRequestTransfer);
        }

        return $dynamicEntityCollectionRequestTransfersByTableAlias;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null> $configurationsIndexedByChildRelationName
     * @param array<string, mixed> $fields
     * @param array<string, mixed> $flattenFieldsByAlias
     *
     * @return array<string, mixed>
     */
    protected function flattenEntityRelations(array $configurationsIndexedByChildRelationName, array $fields, array $flattenFieldsByAlias = []): array
    {
        foreach ($fields as $relationName => $value) {
            $tableAlias = $this->getTableAliasForDynamicEntityConfigurationRelation($configurationsIndexedByChildRelationName, $relationName);
            if ($tableAlias === null) {
                continue;
            }

            foreach ($value as $subfields) {
                $flattenFieldsByAlias[$tableAlias][] = $this->getNonRelationalFields($subfields);

                $relationalFields = $this->getRelationalFields($subfields);
                $flattenFieldsByAlias = $this->flattenEntityRelations($configurationsIndexedByChildRelationName, $relationalFields, $flattenFieldsByAlias);
            }
        }

        return $flattenFieldsByAlias;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null> $configurationsIndexedByChildRelationName
     * @param string $relationName
     *
     * @return string|null
     */
    protected function getTableAliasForDynamicEntityConfigurationRelation(array $configurationsIndexedByChildRelationName, string $relationName): ?string
    {
        $dynamicEntityConfigurationTransfer = $configurationsIndexedByChildRelationName[$relationName] ?? null;
        if ($dynamicEntityConfigurationTransfer === null) {
            return null;
        }

        return $dynamicEntityConfigurationTransfer->getTableAlias();
    }

    /**
     * @param string $tableAlias
     * @param array<int, array<mixed>> $entityFieldsCollection
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    protected function createDynamicEntityCollectionRequestTransfer(
        string $tableAlias,
        array $entityFieldsCollection,
        DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionRequestTransfer {
        $dynamicEntityCollectionRequestTransfer = (new DynamicEntityCollectionRequestTransfer())
            ->setIsCreatable($originalDynamicEntityCollectionRequestTransfer->getIsCreatable())
            ->setResetNotProvidedFieldValues($originalDynamicEntityCollectionRequestTransfer->getResetNotProvidedFieldValues())
            ->setTableAlias($tableAlias);

        foreach ($entityFieldsCollection as $fields) {
            $dynamicEntityTransfer = (new DynamicEntityTransfer())->setFields($fields);

            $dynamicEntityCollectionRequestTransfer->addDynamicEntity($dynamicEntityTransfer);
        }

        return $dynamicEntityCollectionRequestTransfer;
    }

    /**
     * @param array<string, mixed> $fields
     * @param array<string> $relationChain
     * @param string|null $parentField
     *
     * @return array<mixed>
     */
    protected function processEntityKeys(array $fields, array $relationChain, ?string $parentField = null): array
    {
        foreach ($fields as $field => $value) {
            if (!is_array($value)) {
                continue;
            }

            $currentRelationChain = [
                $field,
            ];

            foreach ($value as $subfields) {
                $currentRelationChain = $this->processEntityKeys($subfields, $currentRelationChain, $field);
            }

            $relationChain[] = $currentRelationChain;
        }

        return $relationChain;
    }

    /**
     * @param array<string> $resultRelationChain
     * @param array<mixed> $relationChain
     * @param string|null $parentField
     *
     * @return array<string>
     */
    protected function processRelationChain(array $resultRelationChain, array $relationChain, ?string $parentField = null): array
    {
        $currentKey = array_shift($relationChain);
        $chain = sprintf(
            static::FORMAT_RELATION_CHAIN_ELEMENT,
            ($parentField ?? ''),
            $currentKey,
        );

        if ($relationChain === []) {
            $resultRelationChain[] = rtrim($chain, '.');

            return $resultRelationChain;
        }

        foreach ($relationChain as $nextChain) {
            $chainResult = $this->processRelationChain($resultRelationChain, $nextChain, $chain);

            $resultRelationChain = array_merge(
                $resultRelationChain,
                $chainResult,
            );
        }

        return $resultRelationChain;
    }

    /**
     * @param array<string, mixed> $fields
     *
     * @return array<string, mixed>
     */
    protected function getRelationalFields(array $fields): array
    {
        return array_filter($fields, function ($field) {
            return is_array($field);
        });
    }

    /**
     * @param array<string, mixed> $fields
     *
     * @return array<string, mixed>
     */
    protected function getNonRelationalFields(array $fields): array
    {
        return array_filter($fields, function ($field) {
            return !is_array($field);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null>
     */
    protected function getConfigurationsIndexedByChildRelationName(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): array {
        $indexedConfigurationsByRelationName = [];
        foreach ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfigurationTransfer) {
            foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelationTransfer) {
                $indexedConfigurationsByRelationName[$childRelationTransfer->getNameOrFail()] = $childRelationTransfer->getChildDynamicEntityConfiguration();
            }
        }

        return $indexedConfigurationsByRelationName;
    }
}
