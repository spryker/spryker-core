<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Mapper;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldConditionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Spryker\Zed\DynamicEntity\Business\Expander\DynamicEntityPostEditRequestExpanderInterface;
use Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranch;
use Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface;

class DynamicEntityMapper implements DynamicEntityMapperInterface
{
    /**
     * @var string
     */
    protected const FIELDS = 'fields';

    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const VALIDATION = 'validation';

    /**
     * @var string
     */
    protected const DEFINITION = 'definition';

    /**
     * @var string
     */
    protected const KEY_CHILDREN = 'children';

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Expander\DynamicEntityPostEditRequestExpanderInterface
     */
    protected DynamicEntityPostEditRequestExpanderInterface $dynamicEntityPostEditRequestExpander;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Expander\DynamicEntityPostEditRequestExpanderInterface $dynamicEntityPostEditRequestExpander
     */
    public function __construct(DynamicEntityPostEditRequestExpanderInterface $dynamicEntityPostEditRequestExpander)
    {
        $this->dynamicEntityPostEditRequestExpander = $dynamicEntityPostEditRequestExpander;
    }

    /**
     * @param array<string, mixed> $dynamicEntityConfiguration
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function mapDynamicEntityConfigurationToDynamicEntityConfigurationTransfer(
        array $dynamicEntityConfiguration,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer {
        $dynamicEntityConfigurationTransfer->fromArray($dynamicEntityConfiguration, true);

        $dynamicEntityConfigurationTransfer->setDynamicEntityDefinition(
            $this->mapDynamicEntityDefinitionToDynamicEntityDefinitionTransfer(
                $dynamicEntityConfiguration[static::DEFINITION],
                new DynamicEntityDefinitionTransfer(),
            ),
        );

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param array<string, mixed> $definition
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer
     */
    protected function mapDynamicEntityDefinitionToDynamicEntityDefinitionTransfer(
        array $definition,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
    ): DynamicEntityDefinitionTransfer {
        if (!isset($definition[static::FIELDS])) {
            return $dynamicEntityDefinitionTransfer;
        }

        $dynamicEntityDefinitionTransfer->setIdentifier($definition[static::IDENTIFIER]);

        foreach ($definition[static::FIELDS] as $field) {
            $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->fromArray($field, true);

            $dynamicEntityDefinitionTransfer->addFieldDefinition(
                $dynamicEntityFieldDefinitionTransfer,
            );
        }

        return $dynamicEntityDefinitionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $childDynamicEntityCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function mapChildDynamicEntityCollectionTransferToDynamicEntityCollectionTransfer(
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer,
        DynamicEntityCollectionTransfer $childDynamicEntityCollectionTransfer,
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
    ): DynamicEntityCollectionTransfer {
        $relationFieldMappings = $dynamicEntityConfigurationRelationTransfer->getRelationFieldMappings();
        if ($relationFieldMappings->offsetExists(0) === false) {
            return $dynamicEntityCollectionTransfer;
        }

        /**
         * @var \Generated\Shared\Transfer\DynamicEntityRelationFieldMappingTransfer $firstDynamicEntityRelationFieldMappingTransfer
         */
        $firstDynamicEntityRelationFieldMappingTransfer = $relationFieldMappings->offsetGet(0);

        $parentFieldName = $firstDynamicEntityRelationFieldMappingTransfer->getParentFieldNameOrFail();
        $childFieldName = $firstDynamicEntityRelationFieldMappingTransfer->getChildFieldNameOrFail();

        foreach ($dynamicEntityCollectionTransfer->getDynamicEntities() as $dynamicEntity) {
            $dynamicEntityFields = $dynamicEntity->getFields();
            $dynamicEntityId = $dynamicEntityFields[$parentFieldName];

            $dynamicEntityRelationTransfer = $this->mapChildDynamicEntityToDynamicEntityRelationTransfer(
                $childDynamicEntityCollectionTransfer,
                new DynamicEntityRelationTransfer(),
                $dynamicEntityId,
                $childFieldName,
            );

            if ($dynamicEntityRelationTransfer->getDynamicEntities()->count() > 0) {
                $dynamicEntityRelationTransfer->setName($dynamicEntityConfigurationRelationTransfer->getNameOrFail());
                $dynamicEntity->addChildRelation($dynamicEntityRelationTransfer);
            }
        }

        return $dynamicEntityCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $childDynamicEntityCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityRelationTransfer $dynamicEntityRelationTransfer
     * @param int $dynamicEntityId
     * @param string $childFieldName
     *
     * @return \Generated\Shared\Transfer\DynamicEntityRelationTransfer
     */
    protected function mapChildDynamicEntityToDynamicEntityRelationTransfer(
        DynamicEntityCollectionTransfer $childDynamicEntityCollectionTransfer,
        DynamicEntityRelationTransfer $dynamicEntityRelationTransfer,
        int $dynamicEntityId,
        string $childFieldName
    ): DynamicEntityRelationTransfer {
        foreach ($childDynamicEntityCollectionTransfer->getDynamicEntities() as $childDynamicEntityTransfer) {
            $childDynamicEntityFields = $childDynamicEntityTransfer->getFields();
            $childDynamicEntityId = $childDynamicEntityFields[$childFieldName] ?? null;

            if ($dynamicEntityId === $childDynamicEntityId) {
                $dynamicEntityRelationTransfer->addDynamicEntity($childDynamicEntityTransfer);
            }
        }

        return $dynamicEntityRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     * @param array<string> $childMapping
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string>
     */
    protected function mapDynamicEntityConfigurationRelationToChildMappingArray(
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer,
        array $childMapping,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array {
        foreach ($dynamicEntityConfigurationRelationTransfer->getRelationFieldMappings() as $relationFieldMapping) {
            $childMapping[$dynamicEntityConfigurationTransfer->getTableNameOrFail()][$dynamicEntityConfigurationRelationTransfer->getNameOrFail()] = [
                $relationFieldMapping->getParentFieldNameOrFail() => $relationFieldMapping->getChildFieldNameOrFail(),
            ];
        }

        return $childMapping;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string> $childMapping
     *
     * @return array<string>
     */
    public function getDynamicEntityConfigurationRelationMappedFields(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $childMapping
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childMapping = $this->mapDynamicEntityConfigurationRelationToChildMappingArray($childRelation, $childMapping, $dynamicEntityConfigurationTransfer);
            $childMapping = $this->getDynamicEntityConfigurationRelationMappedFields($childRelation->getChildDynamicEntityConfigurationOrFail(), $childMapping);
        }

        return $childMapping;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     *
     * @return array<string, array<int|string>>
     */
    public function getForeignKeysGroupedByChildFileldName(
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer,
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
    ): array {
        $relationFieldMappings = $dynamicEntityConfigurationRelationTransfer->getRelationFieldMappings();
        if ($relationFieldMappings->offsetExists(0) === false) {
            return [];
        }

        /**
         * @var \Generated\Shared\Transfer\DynamicEntityRelationFieldMappingTransfer $firstDynamicEntityRelationFieldMappingTransfer
         */
        $firstDynamicEntityRelationFieldMappingTransfer = $relationFieldMappings->offsetGet(0);

        $parentFieldName = $firstDynamicEntityRelationFieldMappingTransfer->getParentFieldName();
        $childFieldName = $firstDynamicEntityRelationFieldMappingTransfer->getChildFieldName();

        $foreignKeyFieldMappingArray = [];
        foreach ($dynamicEntityCollectionTransfer->getDynamicEntities() as $dynamicEntity) {
            $fields = $dynamicEntity->getFields();
            $foreignKeyFieldMappingArray[$childFieldName][] = $fields[$parentFieldName];
        }

        return $foreignKeyFieldMappingArray;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer
     */
    public function mapDynamicEntityCollectionRequestTransferToDynamicEntityCriteriaTransfer(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCriteriaTransfer {
        $dynamicEntityConditionsTransfer = new DynamicEntityConditionsTransfer();
        $dynamicEntityConditionsTransfer->setTableAlias($dynamicEntityCollectionRequestTransfer->getTableAliasOrFail());

        $relationChains = $this->getUniqueRelationChains($dynamicEntityCollectionRequestTransfer);
        $implodedRelationChains = $this->getImplodedRelationChains($relationChains);

        return (new DynamicEntityCriteriaTransfer())
            ->setDynamicEntityConditions($dynamicEntityConditionsTransfer)
            ->setRelationChains($implodedRelationChains);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer>
     */
    public function mapDynamicEntityCollectionResponseTransferToPostEditRequestTransfersArray(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): array {
        $dynamicEntityPostEditRequestTransfers = $this->addPostEditRequestTransfer(
            [],
            $dynamicEntityConfigurationTransfer->getTableNameOrFail(),
        );

        $dynamicEntityPostEditRequestTransfers = $this->dynamicEntityPostEditRequestExpander
            ->expandDynamicEntityCollectionResponseTransferWithRawDynamicEntityTransfers(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityCollectionResponseTransfer->getDynamicEntities(),
                $dynamicEntityPostEditRequestTransfers,
            );

        return $dynamicEntityPostEditRequestTransfers;
    }

    /**
     * @param string $tableAlias
     * @param array<int, array<mixed>> $entityFieldsCollection
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer
     * @param array<\Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface> $dynamicEntityCollectionRequestTreeBranches
     *
     * @return array<\Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface>
     */
    public function mapDynamicEntityCollectionRequestTransfersToCollectionRequestTreeBranches(
        string $tableAlias,
        array $entityFieldsCollection,
        DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer,
        array $dynamicEntityCollectionRequestTreeBranches = []
    ): array {
        foreach ($entityFieldsCollection as $fields) {
            $dynamicEntityCollectionRequestTransfer = (new DynamicEntityCollectionRequestTransfer())
                ->setIsCreatable($originalDynamicEntityCollectionRequestTransfer->getIsCreatable())
                ->setResetNotProvidedFieldValues($originalDynamicEntityCollectionRequestTransfer->getResetNotProvidedFieldValues())
                ->setTableAlias($tableAlias);

            $dynamicEntityTransfer = (new DynamicEntityTransfer())->setFields($fields);
            $dynamicEntityCollectionRequestTransfer->addDynamicEntity($dynamicEntityTransfer);

            $dynamicEntityCollectionRequestTreeBranch = (new DynamicEntityCollectionRequestTreeBranch())
                ->setParentCollectionRequestTransfer($dynamicEntityCollectionRequestTransfer);

            $dynamicEntityCollectionRequestTreeBranches[] = $this->createChildDynamicEntityCollectionRequestTransfers(
                $fields,
                $originalDynamicEntityCollectionRequestTransfer,
                $dynamicEntityCollectionRequestTreeBranch,
            );
        }

        return $dynamicEntityCollectionRequestTreeBranches;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConditionsTransfer|null
     */
    public function mapDynamicEntityTransferToDynamicEntityConditionsTransfer(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): ?DynamicEntityConditionsTransfer {
        $identifierFieldVisibleName = $this->getIdentifierVisibleName($dynamicEntityConfigurationTransfer);

        $identifierValue = $dynamicEntityTransfer->getIdentifier();
        if ($identifierValue === null) {
            $dynamicEntityFields = $dynamicEntityTransfer->getFields();
            $identifierValue = $dynamicEntityFields[static::IDENTIFIER] ?? $dynamicEntityFields[$identifierFieldVisibleName] ?? null;
        }

        if ($identifierValue === null) {
            return null;
        }

        $dynamicEntityConditionsTransfer = new DynamicEntityConditionsTransfer();
        $dynamicEntityConditionsTransfer->addFieldCondition(
            (new DynamicEntityFieldConditionTransfer())
                ->setName($identifierFieldVisibleName)
                ->setValue($identifierValue),
        );

        return $dynamicEntityConditionsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getIdentifierVisibleName(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        $identifier = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getIdentifierOrFail();
        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $fieldDefinitionTransfer) {
            if ($fieldDefinitionTransfer->getFieldNameOrFail() === $identifier) {
                return $fieldDefinitionTransfer->getFieldVisibleNameOrFail();
            }
        }

        return $identifier;
    }

    /**
     * @param array<string, mixed> $fields
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer
     * @param \Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface $dynamicEntityCollectionRequestTreeBranch
     *
     * @return \Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface
     */
    protected function createChildDynamicEntityCollectionRequestTransfers(
        array $fields,
        DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer,
        DynamicEntityCollectionRequestTreeBranchInterface $dynamicEntityCollectionRequestTreeBranch
    ): DynamicEntityCollectionRequestTreeBranchInterface {
        foreach ($fields[static::KEY_CHILDREN] as $childTableAlias => $childEntityFieldsCollection) {
            $childDynamicEntityCollectionRequestTransfer = $this->createChildynamicEntityCollectionRequestByEntityFieldsCollection(
                $originalDynamicEntityCollectionRequestTransfer,
                $childEntityFieldsCollection,
                $childTableAlias,
            );

            $dynamicEntityCollectionRequestTreeBranch->addChildCollectionRequestTransfer($childDynamicEntityCollectionRequestTransfer);
        }

        return $dynamicEntityCollectionRequestTreeBranch;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer
     * @param array<int, mixed> $entityFieldsCollection
     * @param string $tableAlias
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    protected function createChildynamicEntityCollectionRequestByEntityFieldsCollection(
        DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer,
        array $entityFieldsCollection,
        string $tableAlias
    ): DynamicEntityCollectionRequestTransfer {
        $dynamicEntityCollectionRequestTransfer = (new DynamicEntityCollectionRequestTransfer())
            ->setIsCreatable($originalDynamicEntityCollectionRequestTransfer->getIsCreatable())
            ->setResetNotProvidedFieldValues($originalDynamicEntityCollectionRequestTransfer->getResetNotProvidedFieldValues())
            ->setTableAlias($tableAlias);

        foreach ($entityFieldsCollection as $fields) {
            $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
                (new DynamicEntityTransfer())->setFields($fields),
            );
        }

        return $dynamicEntityCollectionRequestTransfer;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer> $dynamicEntityPostEditRequestTransfers
     * @param string $tableName
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer>
     */
    protected function addPostEditRequestTransfer(array $dynamicEntityPostEditRequestTransfers, string $tableName): array
    {
        if (isset($dynamicEntityPostEditRequestTransfers[$tableName])) {
            return $dynamicEntityPostEditRequestTransfers;
        }

        $dynamicEntityPostEditRequestTransfers[$tableName] = (new DynamicEntityPostEditRequestTransfer())
            ->setTableName($tableName);

        return $dynamicEntityPostEditRequestTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return array<int, array<int, string>>
     */
    protected function getUniqueRelationChains(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): array
    {
        $relationChains = [];
        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $relationChains = array_merge($relationChains, $this->collectRelationChains($dynamicEntityTransfer->getFields(), []));
        }

        $relationChains = array_unique(array_map('serialize', $relationChains));

        return array_map('unserialize', $relationChains);
    }

    /**
     * @param array<mixed> $fields
     * @param array<mixed> $parentKeys
     *
     * @return array<int, array<int, string>>
     */
    protected function collectRelationChains(array $fields, array $parentKeys): array
    {
        $relationChainNames = [];

        foreach ($fields as $fieldName => $fieldValue) {
            if (is_array($fieldValue) === false) {
                continue;
            }

            $currentKeys = !is_int($fieldName) ? array_merge($parentKeys, [$fieldName]) : $parentKeys;
            $subResult = $this->collectRelationChains($fieldValue, $currentKeys);

            if ($subResult === []) {
                $relationChainNames[] = $currentKeys;

                continue;
            }

            $relationChainNames = array_merge($relationChainNames, $subResult);
        }

        return $relationChainNames;
    }

    /**
     * @param array<int, array<string>> $relationChains
     *
     * @return array<string>
     */
    protected function getImplodedRelationChains(array $relationChains): array
    {
        return array_map(function (array $keys): string {
            return implode('.', $keys);
        }, $relationChains);
    }
}
