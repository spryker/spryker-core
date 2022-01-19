<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\FunctionalityNotSupportedException;
use Spryker\Zed\AclEntity\Persistence\Exception\InvalidScopeException;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;
use Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface;

class InheritedAclQueryScope implements AclQueryScopeInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface
     */
    protected $aclQueryExpander;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    protected $aclEntityRuleCollectionTransferFilter;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface
     */
    protected $aclEntityRuleCollectionTransferSorter;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface
     */
    protected $queryMerger;

    /**
     * @var array<\Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface>
     */
    protected $queryScopes;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface $aclQueryExpander
     * @param \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
     * @param \Spryker\Zed\AclEntity\Persistence\Sorter\AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface $queryMerger
     * @param array<\Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface> $queryScopes
     */
    public function __construct(
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        AclQueryExpanderInterface $aclQueryExpander,
        AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter,
        AclEntityRuleCollectionTransferSorterInterface $aclEntityRuleCollectionTransferSorter,
        AclEntityQueryMergerInterface $queryMerger,
        array $queryScopes
    ) {
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->aclQueryExpander = $aclQueryExpander;
        $this->aclEntityRuleCollectionTransferFilter = $aclEntityRuleCollectionTransferFilter;
        $this->aclEntityRuleCollectionTransferSorter = $aclEntityRuleCollectionTransferSorter;
        $this->queryMerger = $queryMerger;
        $this->queryScopes = $queryScopes;
    }

    /**
     * @param string $scope
     *
     * @return bool
     */
    public function isSupported(string $scope): bool
    {
        return $scope === AclEntityConstants::SCOPE_INHERITED;
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
    public function applyAclRuleOnSelectQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
            $query->getModelName(),
        );
        $parentAclEntityRules = [];
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
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

            $query = $this->aclQueryExpander->joinEntityParent($query, $parentAclEntityMetadataTransfer);
        }

        if (!$parentAclEntityRules) {
            return $this->getDefaultAclQueryScope()->applyAclRuleOnSelectQuery($query, $aclEntityRuleCollectionTransfer);
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
        foreach ($parentAclEntityRules as $parentClass => $aclEntityRuleCollectionTransfer) {
            $query = $this->queryMerger->mergeQueries(
                $query,
                $this->getSegmentAclQueryScope()->applyAclRuleOnSelectQuery(
                    PropelQuery::from($parentClass),
                    $aclEntityRuleCollectionTransfer,
                ),
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
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnUpdateQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntitySubClass(
            $query->getModelName(),
        );

        $queryScope = $this->getAclQueryScope(
            $aclEntityRuleCollectionTransfer,
            $aclEntityMetadataTransfer,
            AclEntityConstants::OPERATION_MASK_UPDATE,
        );

        return $this->queryMerger->mergeQueries(
            $query,
            $queryScope->applyAclRuleOnUpdateQuery(
                PropelQuery::from($aclEntityMetadataTransfer->getEntityNameOrFail()),
                $aclEntityRuleCollectionTransfer,
            ),
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
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\FunctionalityNotSupportedException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnDeleteQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        throw new FunctionalityNotSupportedException(
            FunctionalityNotSupportedException::INHERITED_SCOPE_NOT_SUPPORTED_MESSAGE,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param int $permissionMask
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    protected function getAclQueryScope(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        int $permissionMask
    ): AclQueryScopeInterface {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferSorter->sortByScopePriority(
            $this->aclEntityRuleCollectionTransferFilter->filterByEntityClassAndPermissionMask(
                $aclEntityRuleCollectionTransfer,
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

                return $this->getAclQueryScope(
                    $aclEntityRuleCollectionTransfer,
                    $parentAclEntityMetadataTransfer,
                    $permissionMask,
                );
            }

            return $this->getAclQueryScopeByName($aclEntityRuleTransfer->getScopeOrFail());
        }

        return $this->getDefaultAclQueryScope();
    }

    /**
     * @param string $scope
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\InvalidScopeException
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    protected function getAclQueryScopeByName(string $scope): AclQueryScopeInterface
    {
        foreach ($this->queryScopes as $queryScope) {
            if ($queryScope->isSupported($scope)) {
                return $queryScope;
            }
        }

        throw new InvalidScopeException($scope);
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
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    protected function getDefaultAclQueryScope(): AclQueryScopeInterface
    {
        return $this->getAclQueryScopeByName(AclEntityConstants::SCOPE_DEFAULT);
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface
     */
    protected function getSegmentAclQueryScope(): AclQueryScopeInterface
    {
        return $this->getAclQueryScopeByName(AclEntityConstants::SCOPE_SEGMENT);
    }
}
