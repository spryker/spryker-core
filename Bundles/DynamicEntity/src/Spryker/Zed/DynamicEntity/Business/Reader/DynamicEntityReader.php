<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Reader;

use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityRelationConfigurationTreeBuilderInterface;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface;

class DynamicEntityReader implements DynamicEntityReaderInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CONFIGURATION_NOT_FOUND = 'dynamic_entity.validation.configuration_not_found';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ALIAS_NAME = '%aliasName%';

    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface
     */
    protected DynamicEntityRepositoryInterface $repository;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface
     */
    protected DynamicEntityMapperInterface $dynamicEntityMapper;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface
     */
    protected DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityRelationConfigurationTreeBuilderInterface
     */
    protected DynamicEntityRelationConfigurationTreeBuilderInterface $dynamicEntityRelationTreeBuilder;

    /**
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface $repository
     * @param \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface $dynamicEntityMapper
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityRelationConfigurationTreeBuilderInterface $dynamicEntityRelationTreeBuilder
     */
    public function __construct(
        DynamicEntityRepositoryInterface $repository,
        DynamicEntityMapperInterface $dynamicEntityMapper,
        DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator,
        DynamicEntityRelationConfigurationTreeBuilderInterface $dynamicEntityRelationTreeBuilder
    ) {
        $this->repository = $repository;
        $this->dynamicEntityMapper = $dynamicEntityMapper;
        $this->dynamicEntityConfigurationTreeValidator = $dynamicEntityConfigurationTreeValidator;
        $this->dynamicEntityRelationTreeBuilder = $dynamicEntityRelationTreeBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getEntityCollection(DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer): DynamicEntityCollectionTransfer
    {
        $dynamicEntityConfigurationCollectionTransfer = $this->repository->getDynamicEntityConfigurationByDynamicEntityCriteria(
            $dynamicEntityCriteriaTransfer,
        );

        $errorTransfer = $this->dynamicEntityConfigurationTreeValidator->validateDynamicEntityConfigurationCollection(
            $dynamicEntityConfigurationCollectionTransfer,
            $dynamicEntityCriteriaTransfer,
        );

        if ($errorTransfer !== null) {
            return (new DynamicEntityCollectionTransfer())
                ->addError($errorTransfer);
        }

        $dynamicEntityConfigurationTransfer = $this->dynamicEntityRelationTreeBuilder
            ->buildDynamicEntityConfigurationTransferTree(
                $dynamicEntityCriteriaTransfer,
                $dynamicEntityConfigurationCollectionTransfer,
            );

        if ($dynamicEntityConfigurationTransfer === null) {
            $errorTransfer = $this->createConfigurationNotFoundErrorTransfer(
                $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail(),
            );

            return (new DynamicEntityCollectionTransfer())
                ->addError($errorTransfer);
        }

        return $this->getEntityCollectionByConfiguration($dynamicEntityCriteriaTransfer, $dynamicEntityConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    protected function getEntityCollectionByConfiguration(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionTransfer {
        $dynamicEntityCollectionTransfer = $this->repository->getEntities(
            $dynamicEntityCriteriaTransfer,
            $dynamicEntityConfigurationTransfer,
        );

        if ($dynamicEntityConfigurationTransfer->getChildRelations()->count() === 0) {
            return $dynamicEntityCollectionTransfer;
        }

        return $this->getEntitiesWithChild(
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityCollectionTransfer,
            $this->dynamicEntityMapper->getDynamicEntityConfigurationRelationMappedFields($dynamicEntityConfigurationTransfer, []),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     * @param array<string> $childMapping
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    protected function getEntitiesWithChild(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer,
        array $childMapping
    ): DynamicEntityCollectionTransfer {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelations) {
            $childDynamicEntityCollectionTransfer = $this->repository->getEntities(
                new DynamicEntityCriteriaTransfer(),
                $childRelations->getChildDynamicEntityConfigurationOrFail(),
                $this->dynamicEntityMapper->getForeignKeysGroupedByChildFileldName(
                    $childRelations,
                    $dynamicEntityCollectionTransfer,
                ),
            );

            $childDynamicEntityCollectionTransfer = $this->getEntitiesWithChild(
                $childRelations->getChildDynamicEntityConfigurationOrFail(),
                $childDynamicEntityCollectionTransfer,
                $childMapping,
            );

            $dynamicEntityCollectionTransfer = $this->dynamicEntityMapper->mapChildDynamicEntityCollectionTransferToDynamicEntityCollectionTransfer(
                $dynamicEntityCollectionTransfer,
                $childDynamicEntityCollectionTransfer,
                $childRelations,
            );
        }

        return $dynamicEntityCollectionTransfer;
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
