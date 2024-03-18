<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence;

use Exception;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldConditionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelation;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelationFieldMapping;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityModelNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityPersistenceFactory getFactory()
 */
class DynamicEntityEntityManager extends AbstractEntityManager implements DynamicEntityEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const ERROR_ENTITY_MODEL_NOT_FOUND = 'Model for table "%s" not found.';

    /**
     * @var string
     */
    protected const SET_METHOD_PLACEHOLDER = 'set%s';

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
    protected const GLOSSARY_KEY_ERROR_MISSING_IDENTIFIER = 'dynamic_entity.validation.missing_identifier';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ENTITY_NOT_FOUND_OR_IDENTIFIER_IS_NOT_CREATABLE = 'dynamic_entity.validation.entity_not_found_or_identifier_is_not_creatable';

    /**
     * @var string
     */
    protected const KEY_CONFIGURATIONS = 'configurations';

    /**
     * @var string
     */
    protected const KEY_RELATION_FIELD_MAPPINGS = 'relation_field_mappings';

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
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();

        $entityClassName = $this->getEntityClassName($dynamicEntityConfigurationTransfer->getTableNameOrFail());

        $dynamicEntityMapper = $this->getFactory()->createDynamicEntityMapper();
        $indexedDynamicEntityConfigurations = $dynamicEntityMapper->getChildDynamicEntityConfigurationsIndexedByTableName($dynamicEntityConfigurationTransfer);
        $indexedChildRelations = $dynamicEntityMapper->getChildTableAliasesIndexedByRelationName($dynamicEntityConfigurationTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            /** @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord */
            $activeRecord = new $entityClassName();

            $errorTransfer = $this->getFactory()->createDynamicEntityFieldCreationPreValidator()->validate(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityTransfer,
                $this->resolveFilterCallback($activeRecord),
            );

            if ($errorTransfer !== null) {
                return $dynamicEntityCollectionResponseTransfer->addError($errorTransfer);
            }

            $dynamicEntityTransfer = $this->getFactory()->createDynamicEntityFieldCreationFilter()->filter(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityTransfer,
                $activeRecord,
            );

            $dynamicEntityCollectionResponseTransfer = $this->processActiveRecordSaving(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityTransfer,
                $activeRecord,
            );

            if ($dynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
                return $dynamicEntityCollectionResponseTransfer;
            }

            $dynamicEntityCollectionResponseTransfer = $this->createChildDynamicEntitiesCollection(
                $dynamicEntityTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $indexedChildRelations,
                $indexedDynamicEntityConfigurations,
                $activeRecord,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityModelNotFoundException
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();

        $dynamicEntityQueryClassName = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->getEntityQueryClass($dynamicEntityConfigurationTransfer->getTableNameOrFail());

        if (!class_exists($dynamicEntityQueryClassName)) {
            throw new DynamicEntityModelNotFoundException(
                sprintf(
                    'Model for table "%s" not found.',
                    $dynamicEntityConfigurationTransfer->getTableNameOrFail(),
                ),
            );
        }

        $identifierFieldVisibleName = $this->getIdentifierVisibleName(
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getIdentifierOrFail(),
            $dynamicEntityConfigurationTransfer,
        );

        $dynamicEntityIsCreatable = (bool)$dynamicEntityCollectionRequestTransfer->getIsCreatable();
        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $dynamicEntityConditionsTransfer = $this->addIdentifierToDynamicEntityConditionsTransfer(
                $dynamicEntityTransfer,
                $identifierFieldVisibleName,
                $dynamicEntityIsCreatable,
            );

            if ($dynamicEntityConditionsTransfer === null && !$dynamicEntityIsCreatable) {
                $this->addErrorToResponseTransfer(
                    $dynamicEntityCollectionResponseTransfer,
                    static::GLOSSARY_KEY_ERROR_MISSING_IDENTIFIER,
                    $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
                );

                continue;
            }

            if ($dynamicEntityConditionsTransfer === null) {
                $dynamicEntityConditionsTransfer = new DynamicEntityConditionsTransfer();
            }

            $activeRecord = $this->resolveActiveRecord(
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityConditionsTransfer,
                $dynamicEntityQueryClassName,
            );

            if ($activeRecord === null) {
                $this->addErrorToResponseTransfer(
                    $dynamicEntityCollectionResponseTransfer,
                    static::GLOSSARY_KEY_ERROR_ENTITY_DOES_NOT_EXIST,
                    $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
                );

                continue;
            }

            $activeRecord = $this->resetFieldValues(
                $activeRecord,
                $dynamicEntityTransfer,
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityCollectionRequestTransfer,
            );

            if (
                $dynamicEntityCollectionRequestTransfer->getIsCreatable() === true &&
                !$this->isAllowCreateWithSetupIdentifier($activeRecord, $dynamicEntityConfigurationTransfer)
            ) {
                $this->addErrorToResponseTransfer(
                    $dynamicEntityCollectionResponseTransfer,
                    static::GLOSSARY_KEY_ERROR_ENTITY_NOT_FOUND_OR_IDENTIFIER_IS_NOT_CREATABLE,
                    $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
                    [
                        static::IDENTIFIER_PLACEHOLDER => sprintf(
                            '%s: %s',
                            $identifierFieldVisibleName,
                            $activeRecord->getByName($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getIdentifierOrFail()),
                        ),
                    ],
                );

                continue;
            }

            $errorTransfer = $this->getFactory()->createDynamicEntityFieldUpdatePreValidator()->validate(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityTransfer,
                $this->resolveFilterCallback($activeRecord),
            );

            if ($errorTransfer !== null) {
                return $dynamicEntityCollectionResponseTransfer->addError($errorTransfer);
            }

            $dynamicEntityTransfer = $this->getFactory()->createDynamicEntityFieldUpdateFilter()->filter(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityTransfer,
                $activeRecord,
            );

            $dynamicEntityCollectionResponseTransfer = $this->processActiveRecordSaving(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityTransfer,
                $activeRecord,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
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
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<mixed> $indexedChildRelations
     * @param array<mixed> $indexedDynamicEntityConfigurations
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $parentActiveRecord
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function createChildDynamicEntitiesCollection(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $indexedChildRelations,
        array $indexedDynamicEntityConfigurations,
        ActiveRecordInterface $parentActiveRecord
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelation) {
            $entityClassName = $this->getEntityClassName($indexedChildRelations[$childRelation->getNameOrFail()]->getChildDynamicEntityConfigurationOrFail()->getTableNameOrFail());

            foreach ($childRelation->getDynamicEntities() as $childDynamicEntity) {
                $relationConfiguration = $indexedDynamicEntityConfigurations[$indexedChildRelations[$childRelation->getNameOrFail()]->getChildDynamicEntityConfigurationOrFail()->getTableNameOrFail()];
                $setMethod = $this->convertChildFieldNameToSetForeignKeyMethod($relationConfiguration);

                /** @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord */
                $activeRecord = new $entityClassName();
                $activeRecord->$setMethod($parentActiveRecord->getPrimaryKey());

                $childDynamicEntity = $this->getFactory()->createDynamicEntityFieldCreationFilter()->filter(
                    $relationConfiguration[static::KEY_CONFIGURATIONS],
                    $childDynamicEntity,
                    $activeRecord,
                );

                $dynamicEntityCollectionResponseTransfer = $this->processActiveRecordSaving(
                    $dynamicEntityCollectionResponseTransfer,
                    $relationConfiguration[static::KEY_CONFIGURATIONS],
                    $childDynamicEntity,
                    $activeRecord,
                    true,
                );

                if ($childDynamicEntity->getChildRelations()->count() > 0 && $activeRecord->getPrimaryKey() !== null) {
                    $dynamicEntityCollectionResponseTransfer = $this->createChildDynamicEntitiesCollection(
                        $childDynamicEntity,
                        $dynamicEntityCollectionResponseTransfer,
                        $indexedChildRelations,
                        $indexedDynamicEntityConfigurations,
                        $activeRecord,
                    );
                }
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param array<mixed> $relationConfiguration
     *
     * @return string
     */
    protected function convertChildFieldNameToSetForeignKeyMethod(array $relationConfiguration): string
    {
        $childFieldName = $relationConfiguration[DynamicEntityConfigurationRelationTransfer::RELATION_FIELD_MAPPINGS]->offsetGet(0)->getChildFieldName();
        $childFieldName = ucwords(preg_replace('/_/', ' ', $childFieldName));
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

        /** @var \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer|null $identifierFieldDefinition */
        $identifierFieldDefinition = array_filter($fieldDefinitions, function (DynamicEntityFieldDefinitionTransfer $fieldDefinition) use ($identifier) {
            return $fieldDefinition->getFieldVisibleNameOrFail() === $identifier;
        })[0] ?? null;

        if ($identifierFieldDefinition === null) {
            return true;
        }

        if ($activeRecord->isNew() && $activeRecord->getByName($identifier) !== null && $identifierFieldDefinition->getIsCreatable() === false) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     * @param bool $isChildEntity
     *
     * @throws \Throwable
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processActiveRecordSaving(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        ActiveRecordInterface $activeRecord,
        bool $isChildEntity = false
    ): DynamicEntityCollectionResponseTransfer {
        /** @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord */
        $activeRecord = $this->getFactory()->createDynamicEntityMapper()->mapDynamicEntityTransferToDynamicEntity(
            $dynamicEntityTransfer,
            $activeRecord,
        );

        try {
            $activeRecord->save();
        } catch (Exception $exception) {
            $exceptionMapper = $this->getFactory()->createExceptionToErrorMapper();
            $errorMessageTransfer = $exceptionMapper->map($exception, $dynamicEntityConfigurationTransfer);

            if ($errorMessageTransfer !== null) {
                return $dynamicEntityCollectionResponseTransfer->addError($errorMessageTransfer);
            }

            throw $exception;
        }

        $dynamicEntityTransfer = $this->getFactory()->createDynamicEntityMapper()->mapEntityRecordToDynamicEntityTransfer(
            $activeRecord,
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
            $dynamicEntityTransfer,
        );

        if ($isChildEntity === false) {
            $dynamicEntityCollectionResponseTransfer->addDynamicEntity($dynamicEntityTransfer);
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer
     * @param string $dynamicEntityQueryClassName
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    protected function resolveActiveRecord(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer,
        string $dynamicEntityQueryClassName
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

        if ($dynamicEntityCollectionRequestTransfer->getIsCreatable() === true) {
            return $dynamicEntityQuery->findOneOrCreate();
        }

        return $dynamicEntityQuery->findOne();
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     *
     * @return callable
     */
    protected function resolveFilterCallback(ActiveRecordInterface $activeRecord): callable
    {
        if ($activeRecord->isNew()) {
            return function (DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer) {
                return $fieldDefinitionTransfer->getIsCreatableOrFail() === true;
            };
        }

        return function (DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer) {
            return $fieldDefinitionTransfer->getIsEditableOrFail() === true;
        };
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

    /**
     * @param string $tableName
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityModelNotFoundException
     *
     * @return string
     */
    protected function getEntityClassName(string $tableName): string
    {
        $entityClassName = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->getEntityClassName($tableName);

        if ($entityClassName === null || !class_exists($entityClassName)) {
            throw new DynamicEntityModelNotFoundException(
                sprintf(static::ERROR_ENTITY_MODEL_NOT_FOUND, $tableName),
            );
        }

        return $entityClassName;
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
}
