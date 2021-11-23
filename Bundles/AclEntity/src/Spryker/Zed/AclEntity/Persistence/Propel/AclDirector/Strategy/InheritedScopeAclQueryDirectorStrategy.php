<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\FunctionalityNotSupportedException;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;
use Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface;

class InheritedScopeAclQueryDirectorStrategy implements AclQueryDirectorStrategyInterface
{
    /**
     * @var \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    protected $aclEntityRuleCollectionTransfer;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolverInterface
     */
    protected $relationResolver;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    protected $aclEntityRuleCollectionTransferFilter;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface
     */
    protected $aclEntityRuleCollectionTransferSorter;

    /**
     * @var array<\Closure>
     */
    protected $aclQueryDirectorStrategyContainer;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface
     */
    protected $queryMerger;

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Resolver\RelationResolverInterface $relationResolver
     * @param \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
     * @param \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface $queryMerger
     * @param array<\Closure> $aclQueryDirectorStrategyContainer
     */
    public function __construct(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        RelationResolverInterface $relationResolver,
        AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter,
        AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter,
        AclEntityQueryMergerInterface $queryMerger,
        array $aclQueryDirectorStrategyContainer
    ) {
        $this->aclEntityRuleCollectionTransfer = $aclEntityRuleCollectionTransferSorter->sortByScopePriority(
            $aclEntityRuleCollectionTransfer,
        );
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->relationResolver = $relationResolver;
        $this->aclEntityRuleCollectionTransferFilter = $aclEntityRuleCollectionTransferFilter;
        $this->aclEntityRuleCollectionTransferSorter = $aclEntityRuleCollectionTransferSorter;
        $this->aclQueryDirectorStrategyContainer = $aclQueryDirectorStrategyContainer;
        $this->queryMerger = $queryMerger;
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
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
            $query->getModelName(),
        );
        $parentAclEntityRules = [];
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($this->aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
            if ($this->hasReadableGlobalScopeParent($aclEntityMetadataTransfer, $aclEntityRuleCollectionTransfer)) {
                return $query;
            }
            $parentAclEntityMetadataTransfer = $this->findReadableSegmentScopeParentMetadata(
                $aclEntityMetadataTransfer,
                $aclEntityRuleCollectionTransfer,
            );
            if (!$parentAclEntityMetadataTransfer) {
                continue;
            }
            $parentAclEntityRules[$parentAclEntityMetadataTransfer->getEntityNameOrFail()] = $this
                ->extendAclEntityRuleCollection(
                    $parentAclEntityRules[$parentAclEntityMetadataTransfer->getEntityNameOrFail()] ?? null,
                    $aclEntityRuleCollectionTransfer,
                    $parentAclEntityMetadataTransfer,
                );

            $query = $this->relationResolver->joinEntityParent($query, $parentAclEntityMetadataTransfer);
        }

        if (!$parentAclEntityRules) {
            return $this->getDefaultScopeAclQueryDirectorStrategy()->applyAclRuleOnSelectQuery($query);
        }

        return $this->applyParentAclEntityRulesOnSelectQuery($query, $parentAclEntityRules);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    protected function hasReadableGlobalScopeParent(
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): bool {
        $readableGlobalScopeParentAclEntityMetadata = $this->findReadableParentMetadataByScope(
            $aclEntityMetadataTransfer,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::SCOPE_GLOBAL,
        );

        return $readableGlobalScopeParentAclEntityMetadata !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    protected function findReadableSegmentScopeParentMetadata(
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): ?AclEntityMetadataTransfer {
        return $this->findReadableParentMetadataByScope(
            $aclEntityMetadataTransfer,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::SCOPE_SEGMENT,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param string $scope
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataTransfer|null
     */
    protected function findReadableParentMetadataByScope(
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        string $scope
    ): ?AclEntityMetadataTransfer {
        if ($aclEntityMetadataTransfer->getIsSubEntity()) {
            return $this->findReadableParentMetadataByScope(
                $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                    $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
                ),
                $aclEntityRuleCollectionTransfer,
                $scope,
            );
        }
        $entityAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferSorter->sortByScopePriority(
            $this->aclEntityRuleCollectionTransferFilter->filterByEntityClassAndPermissionMask(
                $aclEntityRuleCollectionTransfer,
                $aclEntityMetadataTransfer->getEntityNameOrFail(),
                AclEntityConstants::OPERATION_MASK_READ,
            ),
        );
        foreach ($entityAclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if ($aclEntityRuleTransfer->getScopeOrFail() === $scope) {
                return $aclEntityMetadataTransfer;
            }
            if ($aclEntityRuleTransfer->getScopeOrFail() !== AclEntityConstants::SCOPE_INHERITED) {
                continue;
            }
            $parentAclEntityMetadata = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
                $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
            );

            return $this->findReadableParentMetadataByScope(
                $parentAclEntityMetadata,
                $aclEntityRuleCollectionTransfer,
                $scope,
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer|null $currentAclEntityRuleCollectionTransfer
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $parentAclEntityMetadataTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    protected function extendAclEntityRuleCollection(
        ?AclEntityRuleCollectionTransfer $currentAclEntityRuleCollectionTransfer,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityMetadataTransfer $parentAclEntityMetadataTransfer
    ): AclEntityRuleCollectionTransfer {
        if (!$currentAclEntityRuleCollectionTransfer) {
            $currentAclEntityRuleCollectionTransfer = new AclEntityRuleCollectionTransfer();
        }

        $parentAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
            ->filterByScopeEntityClassAndPermissionMask(
                $aclEntityRuleCollectionTransfer,
                AclEntityConstants::SCOPE_SEGMENT,
                $parentAclEntityMetadataTransfer->getEntityNameOrFail(),
                AclEntityConstants::OPERATION_MASK_READ,
            );

        foreach ($parentAclEntityRuleCollectionTransfer->getAclEntityRules() as $parentAclEntityRule) {
            $currentAclEntityRuleCollectionTransfer->addAclEntityRule($parentAclEntityRule);
        }

        return $currentAclEntityRuleCollectionTransfer;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array<\Generated\Shared\Transfer\AclEntityRuleCollectionTransfer> $parentAclEntityRules
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyParentAclEntityRulesOnSelectQuery(
        ModelCriteria $query,
        array $parentAclEntityRules
    ): ModelCriteria {
        /** @var \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer */
        foreach ($parentAclEntityRules as $parentClass => $aclEntityRuleCollectionTransfer) {
            $segmentScopeStrategy = $this->getSegmentScopeAclQueryDirectorStrategy($aclEntityRuleCollectionTransfer);
            $query = $this->queryMerger->mergeQueries(
                $query,
                $segmentScopeStrategy->applyAclRuleOnSelectQuery(PropelQuery::from($parentClass)),
            );
        }

        return $query;
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
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntitySubClass(
            $query->getModelName(),
        );

        $strategy = $this->getAclQueryDirectorStrategyByAclEntityMetadataTransferAndPermissionMask(
            $aclEntityMetadataTransfer,
            AclEntityConstants::OPERATION_MASK_UPDATE,
        );

        return $this->queryMerger->mergeQueries(
            $query,
            $strategy->applyAclRuleOnUpdateQuery(
                PropelQuery::from($aclEntityMetadataTransfer->getEntityNameOrFail()),
            ),
        );
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
        throw new FunctionalityNotSupportedException(
            FunctionalityNotSupportedException::INHERITED_SCOPE_NOT_SUPPORTED_MESSAGE,
        );
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return bool
     */
    public function isCreatable(ActiveRecordInterface $entity): bool
    {
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($this->aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
            $createAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
                ->filterByEntityClassAndPermissionMask(
                    $aclEntityRuleCollectionTransfer,
                    get_class($entity),
                    AclEntityConstants::OPERATION_MASK_CREATE,
                );
            if ($createAclEntityRuleCollectionTransfer->getAclEntityRules()->count() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return bool
     */
    public function isUpdatable(ActiveRecordInterface $entity): bool
    {
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($this->aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
            $updateAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
                ->filterByEntityClassAndPermissionMask(
                    $aclEntityRuleCollectionTransfer,
                    get_class($entity),
                    AclEntityConstants::OPERATION_MASK_UPDATE,
                );
            if (
                $updateAclEntityRuleCollectionTransfer->getAclEntityRules()->count() > 0
                && $this->findReadableRoot($entity, $aclEntityRuleCollectionTransfer)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return bool
     */
    public function isDeletable(ActiveRecordInterface $entity): bool
    {
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($this->aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
            $deleteAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
                ->filterByEntityClassAndPermissionMask(
                    $aclEntityRuleCollectionTransfer,
                    get_class($entity),
                    AclEntityConstants::OPERATION_MASK_DELETE,
                );
            if (
                $deleteAclEntityRuleCollectionTransfer->getAclEntityRules()->count() > 0
                && $this->findReadableRoot($entity, $aclEntityRuleCollectionTransfer)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    public function isReadableQuery(ModelCriteria $query): bool
    {
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            $query->getModelName(),
        );
        $rootAclEntityMetadataTransfer = $aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()
            ? $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntitySubClass($query->getModelName())
            : $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntityClass($query->getModelName());

        $strategy = $this->getAclQueryDirectorStrategyByAclEntityMetadataTransferAndPermissionMask(
            $rootAclEntityMetadataTransfer,
            AclEntityConstants::OPERATION_MASK_READ,
        );

        return $strategy->isReadableQuery(PropelQuery::from($rootAclEntityMetadataTransfer->getEntityNameOrFail()));
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    public function isDeletableQuery(ModelCriteria $query): bool
    {
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            $query->getModelName(),
        );
        $rootAclEntityMetadataTransfer = $aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()
            ? $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntitySubClass($query->getModelName())
            : $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntityClass($query->getModelName());

        $strategy = $this->getAclQueryDirectorStrategyByAclEntityMetadataTransferAndPermissionMask(
            $rootAclEntityMetadataTransfer,
            AclEntityConstants::OPERATION_MASK_DELETE,
        );

        return $strategy->isDeletableQuery(PropelQuery::from($rootAclEntityMetadataTransfer->getEntityNameOrFail()));
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param int $permissionMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    protected function getAclQueryDirectorStrategyByAclEntityMetadataTransferAndPermissionMask(
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        int $permissionMask
    ): AclQueryDirectorStrategyInterface {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferSorter->sortByScopePriority(
            $this->aclEntityRuleCollectionTransferFilter->filterByEntityClassAndPermissionMask(
                $this->aclEntityRuleCollectionTransfer,
                $aclEntityMetadataTransfer->getEntityNameOrFail(),
                $permissionMask,
            ),
        );

        foreach ($aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if ($aclEntityRuleTransfer->getScopeOrFail() === AclEntityConstants::SCOPE_INHERITED) {
                $parentAclEntityMetadataTransfer = $this->aclEntityMetadataReader
                    ->getAclEntityMetadataTransferForEntityClass(
                        $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
                    );

                return $this->getAclQueryDirectorStrategyByAclEntityMetadataTransferAndPermissionMask(
                    $parentAclEntityMetadataTransfer,
                    $permissionMask,
                );
            }

            return call_user_func(
                $this->aclQueryDirectorStrategyContainer[$aclEntityRuleTransfer->getScopeOrFail()],
                $this->aclEntityRuleCollectionTransferFilter->filterByScope(
                    $aclEntityRuleCollectionTransfer,
                    $aclEntityRuleTransfer->getScopeOrFail(),
                ),
            );
        }

        return $this->getDefaultScopeAclQueryDirectorStrategy();
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    protected function findReadableRoot(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): ?ActiveRecordInterface {
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
            get_class($entity),
        );
        foreach ($this->relationResolver->getRelationsByAclEntityMetadata($entity, $aclEntityMetadataTransfer) as $relation) {
            $aclEntityRules = $this->aclEntityRuleCollectionTransferFilter->filterByEntityClassAndPermissionMask(
                $aclEntityRuleCollectionTransfer,
                get_class($relation),
                AclEntityConstants::OPERATION_MASK_READ,
            );
            foreach ($aclEntityRules->getAclEntityRules() as $aclEntityRule) {
                if ($aclEntityRule->getScope() === AclEntityConstants::SCOPE_INHERITED) {
                    return $this->findReadableRoot($relation, $aclEntityRuleCollectionTransfer);
                }
                if ($aclEntityRule->getScope() === AclEntityConstants::SCOPE_GLOBAL) {
                    return $relation;
                }
                if (
                    $aclEntityRule->getScope() === AclEntityConstants::SCOPE_SEGMENT
                    && $this->segmentHasEntity($relation, $aclEntityRule->getIdAclEntitySegmentOrFail())
                ) {
                    return $relation;
                }
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\AclEntityRuleCollectionTransfer>
     */
    protected function getGroupedAclEntityRulesByAclGroupId(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): array {
        $result = [];
        foreach ($aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if (!isset($result[$aclEntityRuleTransfer->getIdAclRoleOrFail()])) {
                $result[$aclEntityRuleTransfer->getIdAclRoleOrFail()] = new AclEntityRuleCollectionTransfer();
            }
            $result[$aclEntityRuleTransfer->getIdAclRoleOrFail()]->addAclEntityRule($aclEntityRuleTransfer);
        }

        return $result;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param int $segmentId
     *
     * @return bool
     */
    protected function segmentHasEntity(ActiveRecordInterface $entity, int $segmentId): bool
    {
        /** @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\SegmentScopeAclQueryDirectorStrategy $segmentScopeAclQueryDirectorStrategy */
        $segmentScopeAclQueryDirectorStrategy = $this->getSegmentScopeAclQueryDirectorStrategy(
            new AclEntityRuleCollectionTransfer(),
        );

        return $segmentScopeAclQueryDirectorStrategy->segmentHasEntity($entity, $segmentId);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    protected function getSegmentScopeAclQueryDirectorStrategy(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): AclQueryDirectorStrategyInterface {
        return call_user_func(
            $this->aclQueryDirectorStrategyContainer[AclEntityConstants::SCOPE_SEGMENT],
            $aclEntityRuleCollectionTransfer,
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface
     */
    protected function getDefaultScopeAclQueryDirectorStrategy(): AclQueryDirectorStrategyInterface
    {
        return call_user_func($this->aclQueryDirectorStrategyContainer[AclEntityConstants::SCOPE_DEFAULT]);
    }
}
