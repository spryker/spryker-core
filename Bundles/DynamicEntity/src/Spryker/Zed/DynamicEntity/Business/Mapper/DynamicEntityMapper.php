<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Mapper;

use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationTransfer;

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
}
