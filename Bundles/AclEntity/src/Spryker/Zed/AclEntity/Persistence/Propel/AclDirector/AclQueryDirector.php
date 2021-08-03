<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use InvalidArgumentException;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridgeInterface;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridgeInterface;
use Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface;
use Spryker\Zed\AclEntity\Persistence\Exception\FunctionalityNotSupportedException;
use Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclDirectorStrategyResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class AclQueryDirector implements AclQueryDirectorInterface
{
    /**
     * @var \Propel\Runtime\Collection\ObjectCollection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected $recursionCache;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface
     */
    protected $aclEntityRepository;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclDirectorStrategyResolverInterface
     */
    protected $aclDirectorStrategyResolver;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolverInterface
     */
    protected $relationResolver;

    /**
     * @var \Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridgeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridgeInterface
     */
    protected $aclFacade;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface $aclEntityRepository
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclDirectorStrategyResolverInterface $aclDirectorStrategyResolver
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolverInterface $relationResolver
     * @param \Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridgeInterface $userFacade
     * @param \Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridgeInterface $aclFacade
     */
    public function __construct(
        AclEntityRepositoryInterface $aclEntityRepository,
        AclDirectorStrategyResolverInterface $aclDirectorStrategyResolver,
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        RelationResolverInterface $relationResolver,
        AclEntityToUserFacadeBridgeInterface $userFacade,
        AclEntityToAclFacadeBridgeInterface $aclFacade
    ) {
        $this->recursionCache = new ObjectCollection();
        $this->aclEntityRepository = $aclEntityRepository;
        $this->aclDirectorStrategyResolver = $aclDirectorStrategyResolver;
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->relationResolver = $relationResolver;
        $this->userFacade = $userFacade;
        $this->aclFacade = $aclFacade;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnSelectQuery(ModelCriteria $query): ModelCriteria
    {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRepository->getAclEntityRulesByRoles(
            $this->getRolesTransfer()
        );
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            $query->getModelName()
        );

        $query = $this->applyAclRuleOnSelectQueryRelations($query, $aclEntityRuleCollectionTransfer);

        return $aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()
            ? $this->applyAclRuleToSubEntityQuery($query, $aclEntityRuleCollectionTransfer, $aclEntityMetadataTransfer)
            : $this->applyAclRulesToRootEntityQuery($query, $aclEntityRuleCollectionTransfer);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnUpdateQuery(ModelCriteria $query): ModelCriteria
    {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRepository->getAclEntityRulesByRoles(
            $this->getRolesTransfer()
        );
        $aclQueryDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByQuery(
            $query,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_UPDATE
        );

        return $aclQueryDirectorStrategy->applyAclRuleOnUpdateQuery($query);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\FunctionalityNotSupportedException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnDeleteQuery(ModelCriteria $query): ModelCriteria
    {
        if ($this->isSingleRecordQuery($query)) {
            $entity = (clone $query)->findOne();
            $this->inspectDelete($entity);

            return $query;
        }

        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            $query->getModelName()
        );

        if ($aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()) {
            throw new FunctionalityNotSupportedException(
                FunctionalityNotSupportedException::SUB_ENTITY_NOT_SUPPORTED_MESSAGE
            );
        }

        $aclEntityRuleCollectionTransfer = $this->aclEntityRepository->getAclEntityRulesByRoles(
            $this->getRolesTransfer()
        );
        $aclQueryDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByQuery(
            $query,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_DELETE
        );

        return $aclQueryDirectorStrategy->applyAclRuleOnDeleteQuery($query);
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function inspectCreate(ActiveRecordInterface $entity): void
    {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRepository->getAclEntityRulesByRoles(
            $this->getRolesTransfer()
        );
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            get_class($entity)
        );

        $this->inspectEntityRelations($entity);

        if ($aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()) {
            $this->inspectSubEntityCreate($entity, $aclEntityRuleCollectionTransfer, $aclEntityMetadataTransfer);

            return;
        }

        $this->inspectRootEntityCreate($entity, $aclEntityRuleCollectionTransfer);
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function inspectUpdate(ActiveRecordInterface $entity): void
    {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRepository->getAclEntityRulesByRoles(
            $this->getRolesTransfer()
        );
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            get_class($entity)
        );

        $this->inspectEntityRelations($entity);

        if ($aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()) {
            $this->inspectSubEntityUpdate($entity, $aclEntityRuleCollectionTransfer, $aclEntityMetadataTransfer);

            return;
        }

        $this->inspectRootEntityUpdate($entity, $aclEntityRuleCollectionTransfer);
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function inspectDelete(ActiveRecordInterface $entity): void
    {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRepository->getAclEntityRulesByRoles(
            $this->getRolesTransfer()
        );
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            get_class($entity)
        );
        if ($aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()) {
            $this->inspectSubEntityDelete($entity, $aclEntityRuleCollectionTransfer, $aclEntityMetadataTransfer);

            return;
        }

        $this->inspectRootEntityDelete($entity, $aclEntityRuleCollectionTransfer);
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    protected function inspectRootEntityCreate(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): void {
        $aclDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByEntity(
            $entity,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_CREATE
        );

        if (!$aclDirectorStrategy->isCreatable($entity)) {
            throw new OperationNotAuthorizedException(
                sprintf(
                    OperationNotAuthorizedException::MESSAGE_TEMPLATE,
                    AclEntityConstants::OPERATION_CREATE,
                    get_class($entity)
                )
            );
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $subEntity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    protected function inspectSubEntityCreate(
        ActiveRecordInterface $subEntity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): void {
        $rootEntityAclEntityMetadataTransfer = $this->aclEntityMetadataReader
            ->getRootAclEntityMetadataTransferForEntitySubClass($aclEntityMetadataTransfer->getEntityNameOrFail());
        $rootEntityClass = $rootEntityAclEntityMetadataTransfer->getEntityNameOrFail();

        $rootEntity = new $rootEntityClass();
        $aclQueryDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByEntity(
            $rootEntity,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_CREATE
        );

        if (!$aclQueryDirectorStrategy->isCreatable($rootEntity)) {
            throw new OperationNotAuthorizedException(
                sprintf(
                    OperationNotAuthorizedException::MESSAGE_TEMPLATE,
                    AclEntityConstants::OPERATION_CREATE,
                    get_class($subEntity)
                )
            );
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    protected function inspectRootEntityUpdate(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): void {
        $aclDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByEntity(
            $entity,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_UPDATE
        );

        if (!$aclDirectorStrategy->isUpdatable($entity)) {
            throw new OperationNotAuthorizedException(
                sprintf(
                    OperationNotAuthorizedException::MESSAGE_TEMPLATE,
                    AclEntityConstants::OPERATION_UPDATE,
                    get_class($entity)
                )
            );
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $subEntity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    protected function inspectSubEntityUpdate(
        ActiveRecordInterface $subEntity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): void {
        $rootEntities = $this->relationResolver->getRootRelationsByAclEntityMetadata(
            $subEntity,
            $aclEntityMetadataTransfer
        );
        foreach ($rootEntities as $rootEntity) {
            $aclQueryDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByEntity(
                $rootEntity,
                $aclEntityRuleCollectionTransfer,
                AclEntityConstants::OPERATION_MASK_UPDATE
            );
            if ($aclQueryDirectorStrategy->isUpdatable($rootEntity)) {
                return;
            }
        }

        throw new OperationNotAuthorizedException(
            sprintf(
                OperationNotAuthorizedException::MESSAGE_TEMPLATE,
                AclEntityConstants::OPERATION_UPDATE,
                get_class($subEntity)
            )
        );
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    protected function inspectRootEntityDelete(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): void {
        $aclDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByEntity(
            $entity,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_DELETE
        );

        if (!$aclDirectorStrategy->isDeletable($entity)) {
            throw new OperationNotAuthorizedException(
                sprintf(
                    OperationNotAuthorizedException::MESSAGE_TEMPLATE,
                    AclEntityConstants::OPERATION_DELETE,
                    get_class($entity)
                )
            );
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $subEntity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException
     *
     * @return void
     */
    protected function inspectSubEntityDelete(
        ActiveRecordInterface $subEntity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): void {
        $rootEntities = $this->relationResolver->getRootRelationsByAclEntityMetadata($subEntity, $aclEntityMetadataTransfer);
        foreach ($rootEntities as $rootEntity) {
            $aclQueryDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByEntity(
                $rootEntity,
                $aclEntityRuleCollectionTransfer,
                AclEntityConstants::OPERATION_MASK_DELETE
            );
            if ($aclQueryDirectorStrategy->isDeletable($rootEntity)) {
                return;
            }
        }

        throw new OperationNotAuthorizedException(
            sprintf(
                OperationNotAuthorizedException::MESSAGE_TEMPLATE,
                AclEntityConstants::OPERATION_DELETE,
                get_class($subEntity)
            )
        );
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyAclRulesToRootEntityQuery(
        ModelCriteria $query,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): ModelCriteria {
        $aclQueryDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByQuery(
            $query,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_READ
        );

        return $aclQueryDirectorStrategy->applyAclRuleOnSelectQuery($query);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyAclRuleToSubEntityQuery(
        ModelCriteria $query,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer
    ): ModelCriteria {
        $query = $this->relationResolver->joinSubEntityRootRelation($query, $aclEntityMetadataTransfer);
        $rootAclEntityMetadata = $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntitySubClass(
            $query->getModelName()
        );

        $rootEntityQuery = PropelQuery::from($rootAclEntityMetadata->getEntityNameOrFail());
        $aclQueryDirectorStrategy = $this->aclDirectorStrategyResolver->resolveByQuery(
            $rootEntityQuery,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_READ
        );

        return $query->mergeWith($aclQueryDirectorStrategy->applyAclRuleOnSelectQuery($rootEntityQuery));
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    protected function inspectEntityRelations(ActiveRecordInterface $entity): void
    {
        $this->recursionCache->append($entity);

        $entityTableMap = Propel::getServiceContainer()->getDatabaseMap()->getTableByPhpName((get_class($entity)));
        foreach ($entityTableMap->getRelations() as $relationMap) {
            foreach ($this->relationResolver->getRelationsByRelationMap($entity, $relationMap) as $relation) {
                if ($this->recursionCache->contains($relation)) {
                    continue;
                }
                if ($relation->isNew()) {
                    $this->inspectCreate($relation);
                } elseif ($relation->isModified()) {
                    $this->inspectUpdate($relation);
                }
                $this->recursionCache->append($relation);
            }
        }
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyAclRuleOnSelectQueryRelations(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        $with = [];
        foreach ($query->getWith() as $relation) {
            $relationModelClass = $relation->getModelName();
            $relationAclEntityMetadataTransfer = $this->aclEntityMetadataReader
                ->findAclEntityMetadataTransferForEntityClass($relationModelClass);

            $subQueryModelClass = $relationModelClass;
            if ($relationAclEntityMetadataTransfer && $relationAclEntityMetadataTransfer->getIsSubEntity()) {
                $relationRootAclEntityMetadataTransfer = $this->aclEntityMetadataReader
                    ->getRootAclEntityMetadataTransferForEntitySubClass($relationModelClass);
                $subQueryModelClass = $relationRootAclEntityMetadataTransfer->getEntityNameOrFail();
                $query = $this->relationResolver->joinSubEntityRootRelation($query, $relationAclEntityMetadataTransfer);
            }

            $withQuery = PropelQuery::from($subQueryModelClass);
            $strategy = $this->aclDirectorStrategyResolver->resolveByQuery(
                $withQuery,
                $aclEntityRuleCollectionTransfer,
                AclEntityConstants::OPERATION_MASK_READ
            );

            if (!$strategy->isReadableQuery($withQuery)) {
                $callable = [$withQuery->getTableMap(), 'removeSelectColumns'];

                if (!is_callable($callable)) {
                    throw new InvalidArgumentException(sprintf('Expected a valid callable, %s given.', gettype($callable)));
                }

                // remove columns from select clause
                call_user_func($callable, $query);

                continue;
            }

            $withQuery = $strategy->applyAclRuleOnSelectQuery($withQuery);

            $query = $query->mergeWith($withQuery);

            $with[] = $relation;
        }

        return $query->setWith($with);
    }

    /**
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    protected function getRolesTransfer(): RolesTransfer
    {
        if ($this->userFacade->hasCurrentUser()) {
            return $this->aclFacade->getUserRoles($this->userFacade->getCurrentUser()->getIdUserOrFail());
        }

        return new RolesTransfer();
    }

    /**
     * @phpstan-param iterable<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>&\Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    protected function isSingleRecordQuery(ModelCriteria $query): bool
    {
        if ($query->isSingleRecord()) {
            return true;
        }

        $conditions = $query->getMap();
        if (count($conditions) !== 1) {
            return false;
        }

        $condition = current($conditions);
        $primaryKey = current($query->getTableMap()->getPrimaryKeys());

        if (!$primaryKey) {
            return false;
        }

        return $condition->getColumn() === $primaryKey->getName()
            && $condition->getComparison() === ModelCriteria::EQUAL
            && !is_array($condition->getValue());
    }
}
