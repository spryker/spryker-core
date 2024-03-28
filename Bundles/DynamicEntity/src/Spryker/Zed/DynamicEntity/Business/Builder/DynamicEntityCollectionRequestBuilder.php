<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Builder;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;

class DynamicEntityCollectionRequestBuilder implements DynamicEntityCollectionRequestBuilderInterface
{
    /**
     * @var string
     */
    protected const FORMAT_RELATION_CHAIN_ELEMENT = '%s%s.';

    /**
     * @var string
     */
    protected const KEY_CHILDREN = 'children';

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface
     */
    protected DynamicEntityMapperInterface $dynamicEntityMapper;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface $dynamicEntityMapper
     */
    public function __construct(DynamicEntityMapperInterface $dynamicEntityMapper)
    {
        $this->dynamicEntityMapper = $dynamicEntityMapper;
    }

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
     * @return array<\Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface>
     */
    public function buildDynamicEntityCollectionRequestTreeBranches(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): array {
        $configurationsIndexedByChildRelationName = $this->getConfigurationsIndexedByChildRelationName($dynamicEntityConfigurationCollectionTransfer);

        $mainTableAlias = $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail();
        $flattenFieldsByAlias = [
            $mainTableAlias => [],
        ];
        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $entityFields = $this->getNonRelationalFields($dynamicEntityTransfer->getFields());

            $relationalFields = $this->getRelationalFields($dynamicEntityTransfer->getFields());
            $flattenRelationalFieldsByAlias = $this->flattenEntityRelations($configurationsIndexedByChildRelationName, $relationalFields);

            $flattenFieldsByAlias[$mainTableAlias][] = array_merge($entityFields, $flattenRelationalFieldsByAlias);
        }

        $dynamicEntityCollectionRequestTreeBranches = [];
        foreach ($flattenFieldsByAlias as $tableAlias => $entityFieldsCollection) {
            $dynamicEntityCollectionRequestTreeBranches = $this->dynamicEntityMapper->mapDynamicEntityCollectionRequestTransfersToCollectionRequestTreeBranches(
                $tableAlias,
                $entityFieldsCollection,
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityCollectionRequestTreeBranches,
            );
        }

        return $dynamicEntityCollectionRequestTreeBranches;
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
                $flattenFieldsByAlias[static::KEY_CHILDREN][$tableAlias][] = $this->getNonRelationalFields($subfields);

                $childEntities = $flattenFieldsByAlias[static::KEY_CHILDREN][$tableAlias];
                $relationalFields = $this->getRelationalFields($subfields);
                $flattenFieldsByAlias[static::KEY_CHILDREN][$tableAlias][array_key_last($childEntities)] = $this->flattenEntityRelations(
                    $configurationsIndexedByChildRelationName,
                    $relationalFields,
                    end($childEntities),
                );
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
