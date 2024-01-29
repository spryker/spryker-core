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
    protected const PLACEHOLDER_ALIAS_NAME = '%aliasName%';

    /**
     * @var string
     */
    protected const PLACEHOLDER_RELATION_NAME = '%relationName%';

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
            return $this->createConfigurationNotFoundErrorTransfer($tableAlias);
        }

        $rootDynamicEntityConfigurationTranfer = $this->getDynamicEntityConfigurationEntityByTableAlias(
            $dynamicEntityConfigurationCollectionTransfer,
            $tableAlias,
        );

        if ($rootDynamicEntityConfigurationTranfer === null) {
            return $this->createConfigurationNotFoundErrorTransfer($tableAlias);
        }

        return $this->validateRelationChainsSequence(
            $dynamicEntityConfigurationCollectionTransfer,
            $dynamicEntityCriteriaTransfer,
            $rootDynamicEntityConfigurationTranfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function validateDynamicEntityConfigurationCollectionByDynamicEntityConfigurationCollection(
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
     * @param string $tableAlias
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createConfigurationNotFoundErrorTransfer(string $tableAlias): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage(static::ERROR_MESSAGE_CONFIGURATION_NOT_FOUND)
            ->setParameters([
                static::PLACEHOLDER_ALIAS_NAME => $tableAlias,
            ]);
    }
}
