<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Indexer;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

class DynamicEntityIndexer implements DynamicEntityIndexerInterface
{
    /**
     * @var array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer>
     */
    protected static $cachedDefinitionsIndexedByFieldVisibleName = [];

    /**
     * @var array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    protected static $cachedChildRelationsIndexedByRelationName = [];

    /**
     * @var array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected static $cachedConfigurationsIndexedByTableAlias = [];

    /**
     * @var array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    protected static $cachedChildRelationsIndexedByTableAlias = [];

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<string, string> $indexedFieldDefinitions
     *
     * @return array<string, string>
     */
    public function getFieldValuesIndexedByFieldName(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedFieldDefinitions
    ): array {
        $dynamicEntityFields = [];

        foreach ($dynamicEntityTransfer->getFields() as $fieldName => $fieldValue) {
            $dynamicEntityFields[$indexedFieldDefinitions[$fieldName]] = $fieldValue;
        }

        return $dynamicEntityFields;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<string, string>
     */
    public function getFieldNamesIndexedByFieldVisibleName(
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
    ): array {
        $fieldNamesIndexedByFieldVisibleNames = [];

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinition) {
            $fieldNamesIndexedByFieldVisibleNames[$fieldDefinition->getFieldVisibleNameOrFail()] = $fieldDefinition->getFieldNameOrFail();
        }

        return $fieldNamesIndexedByFieldVisibleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    public function getChildRelationsIndexedByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array {
        if (isset(static::$cachedChildRelationsIndexedByRelationName[$dynamicEntityConfigurationTransfer->getTableAliasOrFail()])) {
            return static::$cachedChildRelationsIndexedByRelationName;
        }

        static::$cachedChildRelationsIndexedByRelationName = $this->indexChildRelationsByRelationName($dynamicEntityConfigurationTransfer);

        return static::$cachedChildRelationsIndexedByRelationName;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    public function getChildRelationsIndexedByTableAlias(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array {
        if (isset(static::$cachedChildRelationsIndexedByTableAlias[$dynamicEntityConfigurationTransfer->getTableAliasOrFail()])) {
            return static::$cachedChildRelationsIndexedByTableAlias;
        }

        static::$cachedChildRelationsIndexedByTableAlias = $this->indexChildRelationsByTableAlias($dynamicEntityConfigurationTransfer);

        return static::$cachedChildRelationsIndexedByTableAlias;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer>
     */
    public function getDefinitionsIndexedByFieldVisibleName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array {
        if (isset(static::$cachedDefinitionsIndexedByFieldVisibleName[$dynamicEntityConfigurationTransfer->getTableAliasOrFail()])) {
            return static::$cachedDefinitionsIndexedByFieldVisibleName;
        }

        $indexedDefinitions = [];
        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $dynamicEntityDefinitionField) {
            $indexedDefinitions[$dynamicEntityConfigurationTransfer->getTableAliasOrFail()][$dynamicEntityDefinitionField->getFieldVisibleNameOrFail()] = $dynamicEntityDefinitionField;
        }

        static::$cachedDefinitionsIndexedByFieldVisibleName = $this->getChildDefinitionsIndexedByFieldVisibleName($dynamicEntityConfigurationTransfer, $indexedDefinitions);

        return static::$cachedDefinitionsIndexedByFieldVisibleName;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    public function getConfigurationsIndexedByTableAlias(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array {
        if (isset(static::$cachedConfigurationsIndexedByTableAlias[$dynamicEntityConfigurationTransfer->getTableAliasOrFail()])) {
            return static::$cachedConfigurationsIndexedByTableAlias;
        }

        $indexedConfigurations = [
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail() => $dynamicEntityConfigurationTransfer,
        ];

        static::$cachedConfigurationsIndexedByTableAlias = $this->indexChildConfigurationsByTableAlias($dynamicEntityConfigurationTransfer, $indexedConfigurations);

        return static::$cachedConfigurationsIndexedByTableAlias;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $indexedChildRelations
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    protected function indexChildRelationsByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildRelations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();

            $indexedChildRelations[$childRelation->getNameOrFail()] = $childRelation;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedChildRelations = $this->indexChildRelationsByRelationName($childDynamicEntityConfigurationTransfer, $indexedChildRelations);
            }
        }

        return $indexedChildRelations;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $indexedChildRelations
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    protected function indexChildRelationsByTableAlias(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildRelations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();

            $indexedChildRelations[$childDynamicEntityConfigurationTransfer->getTableAliasOrFail()] = $childRelation;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedChildRelations = $this->indexChildRelationsByTableAlias($childDynamicEntityConfigurationTransfer, $indexedChildRelations);
            }
        }

        return $indexedChildRelations;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $indexedChildConfigurations
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function indexChildConfigurationsByTableAlias(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildConfigurations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();

            $indexedChildConfigurations[$childDynamicEntityConfigurationTransfer->getTableAliasOrFail()] = $childDynamicEntityConfigurationTransfer;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedChildConfigurations = $this->indexChildConfigurationsByTableAlias($childDynamicEntityConfigurationTransfer, $indexedChildConfigurations);
            }
        }

        return $indexedChildConfigurations;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<mixed> $indexedDefinitions
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer>
     */
    protected function getChildDefinitionsIndexedByFieldVisibleName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedDefinitions
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfiguration = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $tableAlias = $childDynamicEntityConfiguration->getTableAliasOrFail();

            if (isset($indexedDefinitions[$tableAlias])) {
                continue;
            }

            $indexedDefinitions = $this->addFieldDefinionsToIndexedDefinitions($childDynamicEntityConfiguration, $indexedDefinitions);

            if ($childDynamicEntityConfiguration->getChildRelations()->count() > 0) {
                $indexedDefinitions = $this->getChildDefinitionsIndexedByFieldVisibleName($childDynamicEntityConfiguration, $indexedDefinitions);
            }
        }

        return $indexedDefinitions;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<mixed> $indexedDefinitions
     *
     * @return array<mixed>
     */
    protected function addFieldDefinionsToIndexedDefinitions(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedDefinitions
    ): array {
        $tableAlias = $dynamicEntityConfigurationTransfer->getTableAliasOrFail();
        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $dynamicEntityDefinitionField) {
            $indexedDefinitions[$tableAlias][$dynamicEntityDefinitionField->getFieldVisibleNameOrFail()] = $dynamicEntityDefinitionField;
        }

        return $indexedDefinitions;
    }
}
