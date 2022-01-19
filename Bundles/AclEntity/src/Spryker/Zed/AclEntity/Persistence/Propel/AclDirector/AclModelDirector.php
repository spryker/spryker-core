<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\ServiceContainer\ServiceContainerInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclModelScopeResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProviderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReaderInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class AclModelDirector implements AclModelDirectorInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProviderInterface
     */
    protected $aclEntityRuleProvider;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclModelScopeResolverInterface
     */
    protected $aclModelScopeResolver;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReaderInterface
     */
    protected $aclRelationReader;

    /**
     * @var \Propel\Runtime\ServiceContainer\ServiceContainerInterface
     */
    protected $propelServiceContainer;

    /**
     * @var \Propel\Runtime\Collection\ObjectCollection|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    protected $relationCache;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProviderInterface $aclEntityRuleProvider
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclModelScopeResolverInterface $aclModelScopeResolver
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReaderInterface $aclRelationReader
     * @param \Propel\Runtime\ServiceContainer\ServiceContainerInterface $propelServiceContainer
     */
    public function __construct(
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        AclEntityRuleProviderInterface $aclEntityRuleProvider,
        AclModelScopeResolverInterface $aclModelScopeResolver,
        AclRelationReaderInterface $aclRelationReader,
        ServiceContainerInterface $propelServiceContainer
    ) {
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->aclEntityRuleProvider = $aclEntityRuleProvider;
        $this->aclRelationReader = $aclRelationReader;
        $this->aclModelScopeResolver = $aclModelScopeResolver;
        $this->propelServiceContainer = $propelServiceContainer;
        $this->relationCache = new ObjectCollection();
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function inspectCreate(ActiveRecordInterface $entity): void
    {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleProvider->getCurrentUserAclEntityRules();
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            get_class($entity),
        );

        $this->inspectRelations($entity);

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
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleProvider->getCurrentUserAclEntityRules();
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            get_class($entity),
        );

        $this->inspectRelations($entity);

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
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleProvider->getCurrentUserAclEntityRules();
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            get_class($entity),
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
        $aclModelScope = $this->aclModelScopeResolver->resolve(
            $entity,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_CREATE,
        );

        if (!$aclModelScope->isCreatable($entity, $aclEntityRuleCollectionTransfer)) {
            throw new OperationNotAuthorizedException(AclEntityConstants::OPERATION_CREATE, get_class($entity));
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

        /** @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface $rootEntity */
        $rootEntity = new $rootEntityClass();
        $aclModelScope = $this->aclModelScopeResolver->resolve(
            $rootEntity,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_CREATE,
        );

        if (!$aclModelScope->isCreatable($rootEntity, $aclEntityRuleCollectionTransfer)) {
            throw new OperationNotAuthorizedException(AclEntityConstants::OPERATION_CREATE, get_class($subEntity));
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
        $aclModelScope = $this->aclModelScopeResolver->resolve(
            $entity,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_UPDATE,
        );

        if (!$aclModelScope->isUpdatable($entity, $aclEntityRuleCollectionTransfer)) {
            throw new OperationNotAuthorizedException(AclEntityConstants::OPERATION_UPDATE, get_class($entity));
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
        $rootEntities = $this->aclRelationReader->getRootRelationsByAclEntityMetadata(
            $subEntity,
            $aclEntityMetadataTransfer,
        );
        foreach ($rootEntities as $rootEntity) {
            $aclModelScope = $this->aclModelScopeResolver->resolve(
                $rootEntity,
                $aclEntityRuleCollectionTransfer,
                AclEntityConstants::OPERATION_MASK_UPDATE,
            );
            if ($aclModelScope->isUpdatable($rootEntity, $aclEntityRuleCollectionTransfer)) {
                return;
            }
        }

        throw new OperationNotAuthorizedException(AclEntityConstants::OPERATION_UPDATE, get_class($subEntity));
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
        $aclModelScope = $this->aclModelScopeResolver->resolve(
            $entity,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_DELETE,
        );

        if (!$aclModelScope->isDeletable($entity, $aclEntityRuleCollectionTransfer)) {
            throw new OperationNotAuthorizedException(AclEntityConstants::OPERATION_DELETE, get_class($entity));
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
        $rootEntities = $this->aclRelationReader->getRootRelationsByAclEntityMetadata($subEntity, $aclEntityMetadataTransfer);
        foreach ($rootEntities as $rootEntity) {
            $aclModelScope = $this->aclModelScopeResolver->resolve(
                $rootEntity,
                $aclEntityRuleCollectionTransfer,
                AclEntityConstants::OPERATION_MASK_DELETE,
            );
            if ($aclModelScope->isDeletable($rootEntity, $aclEntityRuleCollectionTransfer)) {
                return;
            }
        }

        throw new OperationNotAuthorizedException(AclEntityConstants::OPERATION_DELETE, get_class($subEntity));
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    protected function inspectRelations(ActiveRecordInterface $entity): void
    {
        $this->relationCache->append($entity);

        $entityTableMap = $this->propelServiceContainer->getDatabaseMap()->getTableByPhpName((get_class($entity)));
        foreach ($entityTableMap->getRelations() as $relationMap) {
            $this->inspectRelationMap($entity, $relationMap);
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Propel\Runtime\Map\RelationMap $relationMap
     *
     * @return void
     */
    protected function inspectRelationMap(ActiveRecordInterface $entity, RelationMap $relationMap): void
    {
        foreach ($this->aclRelationReader->getRelationsByRelationMap($entity, $relationMap) as $relation) {
            if ($this->relationCache->contains($relation)) {
                continue;
            }
            if ($relation->isNew()) {
                $this->inspectCreate($relation);
                $this->relationCache->append($relation);

                continue;
            }
            if ($relation->isModified()) {
                $this->inspectUpdate($relation);
                $this->relationCache->append($relation);
            }
        }
    }
}
