<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;

class DynamicEntityConfigurationTreeValidator implements DynamicEntityConfigurationTreeValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CONFIGURATION_NOT_FOUND = 'dynamic_entity.validation.configuration_not_found';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_RELATION_NOT_FOUND = 'dynamic_entity.validation.relation_not_found';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILTER_FIELD_NOT_FOUND = 'dynamic_entity.validation.filter_field_not_found';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ALIAS_NAME = '%aliasName%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_RELATION_NAME = '%relationName%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_FILTER_FIELD = '%filterField%';

    /**
     * @var string
     */
    protected const IDENTIFIER_KEY = 'identifier';

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
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function validateDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer,
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): ?ErrorTransfer {
        $tableAlias = $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail();
        if ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations()->count() === 0) {
            return $this->createErrorTransfer(
                static::ERROR_MESSAGE_CONFIGURATION_NOT_FOUND,
                [
                    static::PLACEHOLDER_ALIAS_NAME => $tableAlias,
                ],
            );
        }

        $rootDynamicEntityConfigurationTransfer = $this->getDynamicEntityConfigurationEntityByTableAlias(
            $dynamicEntityConfigurationCollectionTransfer,
            $tableAlias,
        );

        if ($rootDynamicEntityConfigurationTransfer === null) {
            return $this->createErrorTransfer(
                static::ERROR_MESSAGE_CONFIGURATION_NOT_FOUND,
                [
                    static::PLACEHOLDER_ALIAS_NAME => $tableAlias,
                ],
            );
        }

        $filterFieldValidationError = $this->validateFilterField(
            $dynamicEntityCriteriaTransfer,
            $rootDynamicEntityConfigurationTransfer,
        );

        if ($filterFieldValidationError !== null) {
            return $filterFieldValidationError;
        }

        return $this->validateRelationChainsSequence(
            $dynamicEntityConfigurationCollectionTransfer,
            $dynamicEntityCriteriaTransfer,
            $rootDynamicEntityConfigurationTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function validateDynamicEntityCollectionRequestByDynamicEntityConfigurationCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): ?ErrorTransfer {
        $dynamicEntityCriteriaTransfer = $this->dynamicEntityMapper->mapDynamicEntityCollectionRequestTransferToDynamicEntityCriteriaTransfer($dynamicEntityCollectionRequestTransfer);

        return $this->validateDynamicEntityConfigurationCollection(
            $dynamicEntityConfigurationCollectionTransfer,
            $dynamicEntityCriteriaTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTranfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    protected function validateRelationChainsSequence(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer,
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTranfer
    ): ?ErrorTransfer {
        $relationChains = $this->getRelationChains($dynamicEntityCriteriaTransfer);
        foreach ($relationChains as $relationChain) {
            $errorTransfer = $this->validateRelationChain(
                $relationChain,
                $dynamicEntityConfigurationTranfer,
                $dynamicEntityConfigurationCollectionTransfer,
            );

            if ($errorTransfer !== null) {
                return $errorTransfer;
            }
        }

        return null;
    }

    /**
     * @param array<string> $relationChain
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTranfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    protected function validateRelationChain(
        array $relationChain,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTranfer,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): ?ErrorTransfer {
        foreach ($relationChain as $relationName) {
            $childDynamicEntityConfigurationTranfer = $this->getDynamicEntityConfigurationByChildRelationName(
                $dynamicEntityConfigurationTranfer,
                $relationName,
            );

            if ($childDynamicEntityConfigurationTranfer === null) {
                return (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_RELATION_NOT_FOUND)
                    ->setParameters([
                        static::PLACEHOLDER_RELATION_NAME => $relationName,
                    ]);
            }

            $dynamicEntityConfigurationTranfer = $this->getDynamicEntityConfigurationEntityByTableAlias(
                $dynamicEntityConfigurationCollectionTransfer,
                $childDynamicEntityConfigurationTranfer->getTableAliasOrFail(),
            );

            if ($dynamicEntityConfigurationTranfer === null) {
                return null;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTranfer
     * @param string $relationName
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null
     */
    protected function getDynamicEntityConfigurationByChildRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTranfer,
        string $relationName
    ): ?DynamicEntityConfigurationTransfer {
        foreach ($dynamicEntityConfigurationTranfer->getChildRelations() as $childRelation) {
            if ($childRelation->getName() === $relationName) {
                return $childRelation->getChildDynamicEntityConfiguration();
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return array<int, array<string>>
     */
    protected function getRelationChains(DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer): array
    {
        $relationChains = [];
        foreach ($dynamicEntityCriteriaTransfer->getRelationChains() as $relationChain) {
            $relationNamesFromChain = explode('.', trim($relationChain));

            $relationChains[] = $relationNamesFromChain;
        }

        return $relationChains;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollection
     * @param string $tableAlias
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null
     */
    protected function getDynamicEntityConfigurationEntityByTableAlias(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollection,
        string $tableAlias
    ): ?DynamicEntityConfigurationTransfer {
        foreach ($dynamicEntityConfigurationCollection->getDynamicEntityConfigurations() as $dynamicEntityConfigurationTransfer) {
            if ($dynamicEntityConfigurationTransfer->getTableAlias() === $tableAlias) {
                return $dynamicEntityConfigurationTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $message
     * @param array<string, string> $parameters
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(
        string $message,
        array $parameters = []
    ): ErrorTransfer {
        return (new ErrorTransfer())
            ->setMessage($message)
            ->setParameters($parameters);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTranfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    protected function validateFilterField(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTranfer
    ): ?ErrorTransfer {
        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTranfer->getDynamicEntityDefinitionOrFail();
        $definedFieldNames = $this->collectDefinedFieldVisibleNames($dynamicEntityDefinitionTransfer);

        foreach ($dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getFieldConditions() as $fieldCondition) {
            $fieldConditionName = $fieldCondition->getNameOrFail();

            if ($fieldConditionName === static::IDENTIFIER_KEY) {
                $fieldConditionName = $this->getVisibleIdentifier($dynamicEntityDefinitionTransfer, $fieldConditionName);
            }

            if (!in_array($fieldConditionName, $definedFieldNames)) {
                $tableAlias = $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail();

                return $this->createErrorTransfer(
                    static::ERROR_MESSAGE_FILTER_FIELD_NOT_FOUND,
                    [
                        static::PLACEHOLDER_FILTER_FIELD => $fieldConditionName,
                        static::PLACEHOLDER_ALIAS_NAME => $tableAlias,
                    ],
                );
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<string>
     */
    protected function collectDefinedFieldVisibleNames(DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer): array
    {
        $definedFieldVisibleNames = [];

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinition) {
            $definedFieldVisibleNames[] = $fieldDefinition->getFieldVisibleNameOrFail();
        }

        return $definedFieldVisibleNames;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param string $fieldConditionName
     *
     * @return string
     */
    protected function getVisibleIdentifier(
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        string $fieldConditionName
    ): string {
        $identifier = $dynamicEntityDefinitionTransfer->getIdentifierOrFail();
        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinition) {
            if ($fieldDefinition->getFieldNameOrFail() === $identifier) {
                $fieldConditionName = $fieldDefinition->getFieldVisibleNameOrFail();
            }
        }

        return $fieldConditionName;
    }
}
