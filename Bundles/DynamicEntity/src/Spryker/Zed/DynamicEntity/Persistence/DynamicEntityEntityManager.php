<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence;

use Exception;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldConditionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationFieldMappingTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelation;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelationFieldMapping;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityPersistenceFactory getFactory()
 */
class DynamicEntityEntityManager extends AbstractEntityManager implements DynamicEntityEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const IDENTIFIER_PLACEHOLDER = '%identifier%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ENTITY_DOES_NOT_EXIST = 'dynamic_entity.validation.entity_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ENTITY_NOT_FOUND_OR_IDENTIFIER_IS_NOT_CREATABLE = 'dynamic_entity.validation.entity_not_found_or_identifier_is_not_creatable';

    /**
     * @var string
     */
    protected const KEY_RELATION_FIELD_MAPPINGS = 'relation_field_mappings';

    /**
     * @var string
     */
    protected const IDENTIFIER_INFO_PLACEHOLDER = '%s.%s = %s';

    /**
     * @var string
     */
    protected const PREFIX_GETTER_METHOD = 'get';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function createDynamicEntityConfiguration(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer {
        $dynamicEntityMapper = $this->getFactory()->createDynamicEntityMapper();

        $dynamicEntityConfigurationEntity = $dynamicEntityMapper->mapDynamicEntityConfigurationTransferToEntity($dynamicEntityConfigurationTransfer, new SpyDynamicEntityConfiguration());

        $dynamicEntityConfigurationEntity->save();

        return $dynamicEntityMapper->mapDynamicEntityConfigurationToTransfer($dynamicEntityConfigurationEntity, new DynamicEntityConfigurationTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function updateDynamicEntityConfiguration(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer {
        $dynamicEntityConfigurationEntity = $this->getFactory()
            ->createDynamicEntityConfigurationQuery()
            ->filterByIdDynamicEntityConfiguration($dynamicEntityConfigurationTransfer->getIdDynamicEntityConfiguration())
            ->findOne();

        if (!$dynamicEntityConfigurationEntity) {
            return $dynamicEntityConfigurationTransfer;
        }

        $dynamicEntityMapper = $this->getFactory()->createDynamicEntityMapper();
        $dynamicEntityConfigurationEntity = $dynamicEntityMapper->mapDynamicEntityConfigurationTransferToEntity($dynamicEntityConfigurationTransfer, $dynamicEntityConfigurationEntity);

        $dynamicEntityConfigurationEntity->save();

        return $dynamicEntityMapper->mapDynamicEntityConfigurationToTransfer($dynamicEntityConfigurationEntity, new DynamicEntityConfigurationTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();
        $entityClassName = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->assertEntityClassNameExists($dynamicEntityConfigurationTransfer->getTableNameOrFail());

        /** @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord */
        $activeRecord = new $entityClassName();

        return $this->processActiveRecordSaving(
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityTransfer,
            $activeRecord,
            $dynamicEntityCollectionResponseTransfer,
            $errorPath,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createChildDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationRelationTransfer->getChildDynamicEntityConfigurationOrFail();
        $relationFieldMappingTransfer = $dynamicEntityConfigurationRelationTransfer->getRelationFieldMappings()->offsetGet(0);

        $entityClassName = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->assertEntityClassNameExists($dynamicEntityConfigurationTransfer->getTableNameOrFail());
        $setForeignKeyMethod = $this->convertChildFieldNameToSetForeignKeyMethod($relationFieldMappingTransfer);

        /** @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord */
        $activeRecord = new $entityClassName();
        $activeRecord->$setForeignKeyMethod(
            $dynamicEntityTransfer->getFields()[$relationFieldMappingTransfer->getChildFieldNameOrFail()],
        );

        return $this->processActiveRecordSaving(
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityTransfer,
            $activeRecord,
            $dynamicEntityCollectionResponseTransfer,
            $errorPath,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();
        $dynamicEntityQueryClassName = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->assertEntityQueryClassNameExists($dynamicEntityConfigurationTransfer->getTableNameOrFail());

        $isCreatable = (bool)$dynamicEntityCollectionRequestTransfer->getIsCreatable();
        $activeRecord = $this->resolveActiveRecord(
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityConditionsTransfer,
            $dynamicEntityQueryClassName,
            $isCreatable,
        );

        if ($activeRecord === null) {
            return $this->addErrorToResponseTransfer(
                $dynamicEntityCollectionResponseTransfer,
                static::GLOSSARY_KEY_ERROR_ENTITY_DOES_NOT_EXIST,
                $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
                [DynamicEntityConfig::ERROR_PATH => $errorPath],
            );
        }

        $activeRecord = $this->resetFieldValues(
            $activeRecord,
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityCollectionRequestTransfer,
        );

        if (
            $isCreatable &&
            !$this->isAllowCreateWithSetupIdentifier($activeRecord, $dynamicEntityConfigurationTransfer)
        ) {
            return $this->addIdentifierIsNotCreatableError(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityConfigurationTransfer,
                $activeRecord,
                $errorPath,
            );
        }

        return $this->processActiveRecordSaving(
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityTransfer,
            $activeRecord,
            $dynamicEntityCollectionResponseTransfer,
            $errorPath,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateChildDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer,
        DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationRelationTransfer->getChildDynamicEntityConfigurationOrFail();

        $dynamicEntityQueryClassName = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->assertEntityQueryClassNameExists($dynamicEntityConfigurationTransfer->getTableNameOrFail());

        $isCreatable = (bool)$dynamicEntityCollectionRequestTransfer->getIsCreatable();
        $activeRecord = $this->resolveActiveRecord(
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityConditionsTransfer,
            $dynamicEntityQueryClassName,
            $isCreatable,
        );

        if ($activeRecord === null) {
            return $this->addErrorToResponseTransfer(
                $dynamicEntityCollectionResponseTransfer,
                static::GLOSSARY_KEY_ERROR_ENTITY_DOES_NOT_EXIST,
                $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
                [DynamicEntityConfig::ERROR_PATH => $errorPath],
            );
        }

        $activeRecord = $this->resetFieldValues(
            $activeRecord,
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityCollectionRequestTransfer,
        );

        if ($activeRecord->isNew() === true) {
            $relationFieldMappingTransfer = $dynamicEntityConfigurationRelationTransfer->getRelationFieldMappings()->offsetGet(0);
            $setForeignKeyMethod = $this->convertChildFieldNameToSetForeignKeyMethod($relationFieldMappingTransfer);
            $activeRecord->$setForeignKeyMethod(
                $dynamicEntityTransfer->getFields()[$relationFieldMappingTransfer->getChildFieldNameOrFail()],
            );
        }

        if (
            $dynamicEntityCollectionRequestTransfer->getIsCreatable() &&
            !$this->isAllowCreateWithSetupIdentifier($activeRecord, $dynamicEntityConfigurationTransfer)
        ) {
            return $this->addIdentifierIsNotCreatableError(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityConfigurationTransfer,
                $activeRecord,
                $errorPath,
            );
        }

        return $this->processActiveRecordSaving(
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityTransfer,
            $activeRecord,
            $dynamicEntityCollectionResponseTransfer,
            $errorPath,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $childDynamicEntityConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer
     * @param array<string, array<string, mixed>> $indexedChildRelations
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function createDynamicEntityConfigurationRelation(
        DynamicEntityConfigurationCollectionTransfer $childDynamicEntityConfigurationCollectionTransfer,
        DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer,
        array $indexedChildRelations
    ): DynamicEntityConfigurationCollectionTransfer {
        foreach ($childDynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $childDynamicEntityConfigurationTransfer) {
            $dynamicEntityConfigurationRelationEntity = (new SpyDynamicEntityConfigurationRelation())
                ->fromArray($indexedChildRelations[$childDynamicEntityConfigurationTransfer->getTableAliasOrFail()])
                ->setFkParentDynamicEntityConfiguration($parentDynamicEntityConfigurationTransfer->getIdDynamicEntityConfigurationOrFail())
                ->setFkChildDynamicEntityConfiguration($childDynamicEntityConfigurationTransfer->getIdDynamicEntityConfigurationOrFail());
            $dynamicEntityConfigurationRelationEntity->save();

            (new SpyDynamicEntityConfigurationRelationFieldMapping())
                ->fromArray($indexedChildRelations[$childDynamicEntityConfigurationTransfer->getTableAliasOrFail()][static::KEY_RELATION_FIELD_MAPPINGS][0])
                ->setFkDynamicEntityConfigurationRelation($dynamicEntityConfigurationRelationEntity->getIdDynamicEntityConfigurationRelation())
                ->save();
        }

        return $childDynamicEntityConfigurationCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function deleteDynamicEntity(
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();
        $dynamicEntityQueryClassName = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->assertEntityQueryClassNameExists($dynamicEntityConfigurationTransfer->getTableNameOrFail());

        $dynamicEntityIdentifiers = [];
        foreach ($dynamicEntityCollectionTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $dynamicEntityIdentifiers[] = $dynamicEntityTransfer->getIdentifierOrFail();
        }
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $dynamicEntityQuery */
        $dynamicEntityQuery = new $dynamicEntityQueryClassName();
        $identifier = $this->convertSnakeCaseToCamelCase($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getIdentifierOrFail());
        $objectCollection = $dynamicEntityQuery->filterBy($identifier, $dynamicEntityIdentifiers, Criteria::IN)->find();

        foreach ($objectCollection as $object) {
            try {
                $object->delete();
            } catch (Exception $exception) {
                $getterMethodName = static::PREFIX_GETTER_METHOD . ucfirst($identifier);

                return $this->handleDeleteException(
                    $dynamicEntityCollectionResponseTransfer,
                    $dynamicEntityConfigurationTransfer,
                    $this->generateIdentifierInfo($dynamicEntityConfigurationTransfer, $object->{$getterMethodName}()),
                    $exception,
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityRelationFieldMappingTransfer $relationFieldMappingTransfer
     *
     * @return string
     */
    protected function convertChildFieldNameToSetForeignKeyMethod(DynamicEntityRelationFieldMappingTransfer $relationFieldMappingTransfer): string
    {
        $childFieldName = ucwords((string)preg_replace('/_/', ' ', $relationFieldMappingTransfer->getChildFieldNameOrFail()));
        $childFieldName = str_replace(' ', '', $childFieldName);

        return sprintf('set' . $childFieldName);
    }

    /**
     * @throw \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return bool
     */
    protected function isAllowCreateWithSetupIdentifier(
        ActiveRecordInterface $activeRecord,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): bool {
        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail();
        $identifier = $dynamicEntityDefinitionTransfer->getIdentifierOrFail();

        $fieldDefinitions = $dynamicEntityDefinitionTransfer->getFieldDefinitions()->getArrayCopy();

        /** @var array<\Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $identifierFieldDefinition */
        $identifierFieldDefinition = array_filter($fieldDefinitions, function (DynamicEntityFieldDefinitionTransfer $fieldDefinition) use ($identifier) {
            return $fieldDefinition->getFieldNameOrFail() === $identifier;
        });

        if ($identifierFieldDefinition === []) {
            return true;
        }

        $isCreatable = array_shift($identifierFieldDefinition)->getIsCreatable();
        if ($activeRecord->isNew() && $activeRecord->getByName($identifier) !== null && $isCreatable === false) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processActiveRecordSaving(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        ActiveRecordInterface $activeRecord,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        try {
            /** @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord */
            $activeRecord = $this->getFactory()->createDynamicEntityMapper()->mapDynamicEntityTransferToDynamicEntity(
                $dynamicEntityTransfer,
                $activeRecord,
            );

            $activeRecord->save();
        } catch (Exception $exception) {
            return $this->handleSaveException(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityConfigurationTransfer,
                $errorPath,
                $exception,
            );
        }

        $dynamicEntityTransfer = $this->populateDynamicEntityTransferWithFields($dynamicEntityTransfer, $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(), $activeRecord);
        $dynamicEntityTransfer = $this->addIdentifierToFields($dynamicEntityTransfer, $activeRecord, $dynamicEntityConfigurationTransfer);

        return $dynamicEntityCollectionResponseTransfer->addDynamicEntity($dynamicEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer
     * @param string $dynamicEntityQueryClassName
     * @param bool $isCreatable
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    protected function resolveActiveRecord(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer,
        string $dynamicEntityQueryClassName,
        bool $isCreatable
    ): ?ActiveRecordInterface {
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $dynamicEntityQuery */
        $dynamicEntityQuery = new $dynamicEntityQueryClassName();
        $dynamicEntityCriteriaTransfer = (new DynamicEntityCriteriaTransfer())
            ->setDynamicEntityConditions($dynamicEntityConditionsTransfer);

        $dynamicEntityQuery = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->buildQueryWithFieldConditions(
                $dynamicEntityQuery,
                $dynamicEntityCriteriaTransfer,
                $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
            );

        if ($isCreatable === true) {
            return $dynamicEntityQuery->findOneOrCreate();
        }

        return $dynamicEntityQuery->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param string $identifierFieldVisibleName
     * @param bool $isCreatable
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConditionsTransfer|null
     */
    protected function addIdentifierToDynamicEntityConditionsTransfer(
        DynamicEntityTransfer $dynamicEntityTransfer,
        string $identifierFieldVisibleName,
        bool $isCreatable
    ): ?DynamicEntityConditionsTransfer {
        $identifierValue = null;
        if (isset($dynamicEntityTransfer->getFields()[static::IDENTIFIER]) || isset($dynamicEntityTransfer->getFields()[$identifierFieldVisibleName])) {
            $identifierValue = $dynamicEntityTransfer->getFields()[static::IDENTIFIER] ?? $dynamicEntityTransfer->getFields()[$identifierFieldVisibleName];
        }

        if ($identifierValue === null && $isCreatable === false) {
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
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityTransfer
     */
    protected function addIdentifierToFields(
        DynamicEntityTransfer $dynamicEntityTransfer,
        ActiveRecordInterface $activeRecord,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityTransfer {
        $identifier = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getIdentifierOrFail();
        $identifierVisibleName = $this->getIdentifierVisibleName($dynamicEntityConfigurationTransfer);

        $identifierValue = $activeRecord->getByName($identifier);
        $dynamicEntityTransfer->setFields(array_merge(
            $dynamicEntityTransfer->getFields(),
            [$identifierVisibleName => $identifierValue],
        ));
        $dynamicEntityTransfer->setIdentifier($identifierValue);

        return $dynamicEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     *
     * @return \Generated\Shared\Transfer\DynamicEntityTransfer
     */
    protected function populateDynamicEntityTransferWithFields(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        ActiveRecordInterface $activeRecord
    ): DynamicEntityTransfer {
        $activeRecord = $activeRecord->toArray();
        $entityFields = [];
        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            $fieldName = $fieldDefinitionTransfer->getFieldNameOrFail();
            $fieldVisibleName = $fieldDefinitionTransfer->getFieldVisibleNameOrFail();
            $fieldValue = $activeRecord[$fieldName];
            $entityFields[$fieldVisibleName] = $fieldValue;
        }

        $dynamicEntityTransfer->setFields(array_merge(
            $dynamicEntityTransfer->getFields(),
            $entityFields,
        ));

        return $dynamicEntityTransfer;
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
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    protected function resetFieldValues(
        ActiveRecordInterface $activeRecord,
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): ActiveRecordInterface {
        $resetNotProvidedFields = (bool)$dynamicEntityCollectionRequestTransfer->getResetNotProvidedFieldValues();
        if ($activeRecord->isNew() === true || !$resetNotProvidedFields) {
            return $activeRecord;
        }

        return $this->getFactory()
            ->createDynamicEntityResetter()
            ->resetNotProvidedFields($activeRecord, $dynamicEntityTransfer, $dynamicEntityConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $errorPath
     * @param \Exception $exception
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function handleSaveException(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath,
        Exception $exception
    ): DynamicEntityCollectionResponseTransfer {
        $errorMessageTransfer = $this->getFactory()->createExceptionToErrorMapper()
            ->map($exception, $dynamicEntityConfigurationTransfer, $errorPath);

        if ($errorMessageTransfer !== null) {
            return $dynamicEntityCollectionResponseTransfer->addError($errorMessageTransfer);
        }

        throw $exception;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $errorPath
     * @param \Exception $exception
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function handleDeleteException(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath,
        Exception $exception
    ): DynamicEntityCollectionResponseTransfer {
        $errorMessageTransfer = $this->getFactory()->createExceptionToErrorMapper()
            ->map($exception, $dynamicEntityConfigurationTransfer, $errorPath);

        if ($errorMessageTransfer !== null) {
            return $dynamicEntityCollectionResponseTransfer->addError($errorMessageTransfer);
        }

        throw $exception;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param string $errorMessage
     * @param string $tableAlias
     * @param array<string, string> $customErrorParameters
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function addErrorToResponseTransfer(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        string $errorMessage,
        string $tableAlias,
        array $customErrorParameters = []
    ): DynamicEntityCollectionResponseTransfer {
        $errorMessageTransfer = (new ErrorTransfer())
            ->setEntityIdentifier($tableAlias)
            ->setMessage($errorMessage)
            ->setParameters($customErrorParameters);

        return $dynamicEntityCollectionResponseTransfer->addError($errorMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function addIdentifierIsNotCreatableError(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        ActiveRecordInterface $activeRecord,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        $identifierFieldVisibleName = $this->getIdentifierVisibleName($dynamicEntityConfigurationTransfer);

        return $this->addErrorToResponseTransfer(
            $dynamicEntityCollectionResponseTransfer,
            static::GLOSSARY_KEY_ERROR_ENTITY_NOT_FOUND_OR_IDENTIFIER_IS_NOT_CREATABLE,
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
            [
                static::IDENTIFIER_PLACEHOLDER => sprintf(
                    '%s: %s',
                    $identifierFieldVisibleName,
                    $activeRecord->getByName($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getIdentifierOrFail()),
                ),
                DynamicEntityConfig::ERROR_PATH => $errorPath,
            ],
        );
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
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $identifier
     *
     * @return string
     */
    protected function generateIdentifierInfo(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $identifier
    ): string {
        return sprintf(
            static::IDENTIFIER_INFO_PLACEHOLDER,
            $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
            $this->getIdentifierVisibleName($dynamicEntityConfigurationTransfer),
            $identifier,
        );
    }
}
