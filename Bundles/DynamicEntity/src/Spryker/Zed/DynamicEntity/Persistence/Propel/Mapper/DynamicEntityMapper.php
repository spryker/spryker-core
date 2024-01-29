<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationFieldMappingTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelation;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelationFieldMapping;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class DynamicEntityMapper
{
    /**
     * @var string
     */
    protected const FIELDS = 'fields';

    /**
     * @var string
     */
    protected const FIELD_DEFINITIONS = 'fieldDefinitions';

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
    protected const SET_METHOD_PLACEHOLDER = 'set%s';

    /**
     * @var string
     */
    protected const TYPE_INTEGER = 'integer';

    /**
     * @var string
     */
    protected const KEY_CONFIGURATIONS = 'configurations';

    /**
     * @var string
     */
    protected const KEY_RELATION_FIELD_MAPPINGS = 'relationFieldMappings';

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration $dynamicEntityConfiguration
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function mapDynamicEntityConfigurationToTransfer(
        SpyDynamicEntityConfiguration $dynamicEntityConfiguration,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer {
        $dynamicEntityConfigurationTransfer->fromArray($dynamicEntityConfiguration->toArray(), true);

        $dynamicEntityConfigurationTransfer->setDynamicEntityDefinition(
            $this->mapDynamicEntityDefinitionToDynamicEntityDefinitionTransfer(
                $dynamicEntityConfiguration->getDefinition(),
                new DynamicEntityDefinitionTransfer(),
            ),
        );

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration $dynamicEntityConfigurationEntity
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration
     */
    public function mapDynamicEntityConfigurationTransferToEntity(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        SpyDynamicEntityConfiguration $dynamicEntityConfigurationEntity
    ): SpyDynamicEntityConfiguration {
        $dynamicEntityConfigurationEntity->fromArray($dynamicEntityConfigurationTransfer->toArray());

        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail();
        $definitions = $dynamicEntityDefinitionTransfer->toArray(true, true);
        $modifiedDefinitions = $dynamicEntityDefinitionTransfer->modifiedToArray(true, true);
        $definitionForEntity = [
            static::IDENTIFIER => $definitions[static::IDENTIFIER],
            static::FIELDS => $modifiedDefinitions[static::FIELD_DEFINITIONS] ?? [],
        ];

        $dynamicEntityConfigurationEntity->setDefinition(
            json_encode(
                $definitionForEntity,
            ) ?: '',
        );

        return $dynamicEntityConfigurationEntity;
    }

    /**
     * @param array<mixed> $entityRecordsData
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function mapEntityRecordsToCollectionTransfer(
        array $entityRecordsData,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
    ): DynamicEntityCollectionTransfer {
        $indexedFieldDefinitions = $this->indexDynamicEntityFieldDefinitionsByTableFieldName($dynamicEntityDefinitionTransfer);
        $identifierVisibleName = $this->getIdentifierVisibleName($dynamicEntityDefinitionTransfer->getIdentifierOrFail(), $dynamicEntityDefinitionTransfer);

        foreach ($entityRecordsData as $entityRecord) {
            $dynamicEntityFields = $this->mapRecordFieldsToDynamicEntityFieldsArray($entityRecord, $indexedFieldDefinitions);

            $dynamicEntityTransfer = (new DynamicEntityTransfer())
                ->setFields($dynamicEntityFields)
                ->setIdentifier($dynamicEntityFields[$identifierVisibleName]);

            $dynamicEntityCollectionTransfer->addDynamicEntity($dynamicEntityTransfer);
        }

        return $dynamicEntityCollectionTransfer;
    }

    /**
     * @param array<mixed> $dynamicEntityConfigurationData
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function mapDynamicEntityConfigurationsToCollectionTransfer(
        array $dynamicEntityConfigurationData,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionTransfer {
        foreach ($dynamicEntityConfigurationData as $dynamicEntityConfiguration) {
            $dynamicEntityConfigurationTransfer = $this->mapDynamicEntityConfigurationToTransfer(
                $dynamicEntityConfiguration,
                new DynamicEntityConfigurationTransfer(),
            );

            $dynamicEntityConfigurationTransfer = $this->mapDynamicEntityConfigurationCollectionToDynamicEntityConfigurationTransfers(
                $dynamicEntityConfiguration,
                $dynamicEntityConfigurationTransfer,
            );

            $dynamicEntityConfigurationCollectionTransfer->addDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);
        }

        return $dynamicEntityConfigurationCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    public function mapDynamicEntityTransferToDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        ActiveRecordInterface $activeRecord
    ): ?ActiveRecordInterface {
        foreach ($dynamicEntityTransfer->getFields() as $fieldName => $fieldValue) {
            $setFieldMethod = $this->getSetFieldMethod($fieldName);
            $activeRecord->$setFieldMethod($fieldValue);
        }

        return $activeRecord;
    }

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration $dynamicEntityConfiguration
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function mapDynamicEntityConfigurationCollectionToDynamicEntityConfigurationTransfers(
        SpyDynamicEntityConfiguration $dynamicEntityConfiguration,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer {
        foreach ($dynamicEntityConfiguration->getSpyDynamicEntityConfigurationRelationsRelatedByFkParentDynamicEntityConfiguration() as $dynamicEntityRelationConfigurationRelation) {
            $childConfigurationEntity = $dynamicEntityRelationConfigurationRelation->getSpyDynamicEntityConfigurationRelatedByFkChildDynamicEntityConfiguration();

            $dynamicEntityConfigurationTransfer = $this->mapDynamicEntityConfigurationEntityRelationToDynamicEntityConfigurationTransfer(
                $childConfigurationEntity,
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityRelationConfigurationRelation,
            );
        }

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, string|array<mixed>> $indexedDynamicEntityConfigurations
     *
     * @return array<string, string|array<mixed>>
     */
    public function getChildDynamicEntityConfigurationsIndexedByTableName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedDynamicEntityConfigurations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();
            $tableName = $childDynamicEntityConfigurationTransfer->getTableNameOrFail();

            if (isset($indexedDynamicEntityConfigurations[$tableName])) {
                continue;
            }

            $indexedDynamicEntityConfigurations[$tableName] = [
                static::KEY_CONFIGURATIONS => $childDynamicEntityConfigurationTransfer,
                static::KEY_RELATION_FIELD_MAPPINGS => $childRelation->getRelationFieldMappings(),
            ];

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedDynamicEntityConfigurations = $this->getChildDynamicEntityConfigurationsIndexedByTableName(
                    $childDynamicEntityConfigurationTransfer,
                    $indexedDynamicEntityConfigurations,
                );
            }
        }

        return $indexedDynamicEntityConfigurations;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $indexedChildRelations
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    public function getChildTableAliasesIndexedByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedChildRelations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();

            $indexedChildRelations[$childRelation->getNameOrFail()] = $childRelation;

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedChildRelations = $this->getChildTableAliasesIndexedByRelationName($childDynamicEntityConfigurationTransfer, $indexedChildRelations);
            }
        }

        return $indexedChildRelations;
    }

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration $dynamicEntityConfigurationEntity
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelation $dynamicEntityRelationConfigurationRelation
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    protected function mapDynamicEntityConfigurationEntityRelationToDynamicEntityConfigurationTransfer(
        SpyDynamicEntityConfiguration $dynamicEntityConfigurationEntity,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        SpyDynamicEntityConfigurationRelation $dynamicEntityRelationConfigurationRelation
    ): DynamicEntityConfigurationTransfer {
        $childDynamicEntityConfigurationTransfer = $this->mapDynamicEntityConfigurationToTransfer(
            $dynamicEntityConfigurationEntity,
            new DynamicEntityConfigurationTransfer(),
        );

        $childConfigurationRelationTransfer = (new DynamicEntityConfigurationRelationTransfer())
            ->setName($dynamicEntityRelationConfigurationRelation->getName())
            ->setIsEditable($dynamicEntityRelationConfigurationRelation->getIsEditable())
            ->setChildDynamicEntityConfiguration($childDynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityRelationConfigurationRelation->getSpyDynamicEntityConfigurationRelationFieldMappings() as $fieldMapping) {
            $childConfigurationRelationTransfer = $this->mapDynamicEntityConfigurationRelationToDynamicEntityConfigurationTransfer(
                $fieldMapping,
                $childConfigurationRelationTransfer,
            );
        }

        $dynamicEntityConfigurationTransfer->addChildRelation($childConfigurationRelationTransfer);

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelationFieldMapping $fieldMapping
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer
     */
    protected function mapDynamicEntityConfigurationRelationToDynamicEntityConfigurationTransfer(
        SpyDynamicEntityConfigurationRelationFieldMapping $fieldMapping,
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
    ): DynamicEntityConfigurationRelationTransfer {
        $dynamicEntityRelationFieldMappingTransfer = (new DynamicEntityRelationFieldMappingTransfer())
            ->setParentFieldName($fieldMapping->getParentFieldName())
            ->setChildFieldName($fieldMapping->getChildFieldName());

        $dynamicEntityConfigurationRelationTransfer->addRelationFieldMapping(
            $dynamicEntityRelationFieldMappingTransfer,
        );

        return $dynamicEntityConfigurationRelationTransfer;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    protected function convertSnakeCaseToCamelCase(string $input): string
    {
        return str_replace('_', '', ucwords($input, '_'));
    }

    /**
     * @param string $input
     *
     * @return string
     */
    protected function getSetFieldMethod(string $input): string
    {
        return sprintf(static::SET_METHOD_PLACEHOLDER, $this->convertSnakeCaseToCamelCase($input));
    }

    /**
     * @param string $definition
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer
     */
    protected function mapDynamicEntityDefinitionToDynamicEntityDefinitionTransfer(
        string $definition,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
    ): DynamicEntityDefinitionTransfer {
        $config = json_decode($definition, true);

        if (!isset($config[static::FIELDS])) {
            return $dynamicEntityDefinitionTransfer;
        }

        $dynamicEntityDefinitionTransfer->setIdentifier($config[static::IDENTIFIER]);

        foreach ($config[static::FIELDS] as $field) {
            $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->fromArray($field, true);

            if (!empty($field[static::VALIDATION])) {
                $dynamicEntityFieldDefinitionTransfer->setValidation(
                    (new DynamicEntityFieldValidationTransfer())->fromArray($field[static::VALIDATION], true),
                );
            }

            $dynamicEntityDefinitionTransfer->addFieldDefinition(
                $dynamicEntityFieldDefinitionTransfer,
            );
        }

        return $dynamicEntityDefinitionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<mixed>
     */
    protected function indexDynamicEntityFieldDefinitionsByTableFieldName(DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer): array
    {
        $result = [];

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinition) {
            $result[$fieldDefinition->getFieldNameOrFail()] = $fieldDefinition;
        }

        return $result;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entityRecord
     * @param array<mixed> $indexedFieldDefinitions
     *
     * @return array<mixed>
     */
    protected function mapRecordFieldsToDynamicEntityFieldsArray(
        ActiveRecordInterface $entityRecord,
        array $indexedFieldDefinitions
    ): array {
        $dynamicEntityFields = [];

        foreach ($entityRecord->toArray() as $fieldName => $value) {
            if (!isset($indexedFieldDefinitions[$fieldName])) {
                continue;
            }

            $dynamicEntityFields[$indexedFieldDefinitions[$fieldName]->getFieldVisibleName()] = $this->castTypes($indexedFieldDefinitions[$fieldName]->getType(), $value);
        }

        return $dynamicEntityFields;
    }

    /**
     * @param string $type
     * @param mixed $value
     *
     * @return mixed
     */
    protected function castTypes(string $type, mixed $value): mixed
    {
        return ($type === static::TYPE_INTEGER && $value !== null) ? (int)$value : $value;
    }

    /**
     * @param string $identifier
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return string
     */
    protected function getIdentifierVisibleName(string $identifier, DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer): string
    {
        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            if ($fieldDefinitionTransfer->getFieldNameOrFail() === $identifier) {
                return $fieldDefinitionTransfer->getFieldVisibleNameOrFail();
            }
        }

        return $identifier;
    }
}
