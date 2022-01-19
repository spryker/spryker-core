<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\FunctionalityNotSupportedException;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProviderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class AclQueryDirector implements AclQueryDirectorInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclJoinDirectorInterface
     */
    protected $aclJoinDirector;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProviderInterface
     */
    protected $aclEntityRuleProvider;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolverInterface
     */
    protected $aclQueryScopeResolver;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface
     */
    protected $aclQueryExpander;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface
     */
    protected $aclQueryMerger;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclModelDirectorInterface
     */
    protected $aclModelDirector;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclJoinDirectorInterface $aclJoinDirector
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProviderInterface $aclEntityRuleProvider
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolverInterface $aclQueryScopeResolver
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface $aclQueryExpander
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface $aclQueryMerger
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclModelDirectorInterface $aclModelDirector
     */
    public function __construct(
        AclJoinDirectorInterface $aclJoinDirector,
        AclEntityRuleProviderInterface $aclEntityRuleProvider,
        AclQueryScopeResolverInterface $aclQueryScopeResolver,
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        AclQueryExpanderInterface $aclQueryExpander,
        AclEntityQueryMergerInterface $aclQueryMerger,
        AclModelDirectorInterface $aclModelDirector
    ) {
        $this->aclJoinDirector = $aclJoinDirector;
        $this->aclEntityRuleProvider = $aclEntityRuleProvider;
        $this->aclQueryScopeResolver = $aclQueryScopeResolver;
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->aclQueryExpander = $aclQueryExpander;
        $this->aclQueryMerger = $aclQueryMerger;
        $this->aclModelDirector = $aclModelDirector;
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
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleProvider->getCurrentUserAclEntityRules();
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            $query->getModelName(),
        );
        if ($aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()) {
            $query = $this->applyAclRuleToSubEntityQuery($query, $aclEntityRuleCollectionTransfer, $aclEntityMetadataTransfer);

            return $this->aclJoinDirector->applyAclRuleOnSelectQueryRelations(
                $query,
                $aclEntityRuleCollectionTransfer,
            );
        }

        $query = $this->applyAclRulesToRootEntityQuery($query, $aclEntityRuleCollectionTransfer);

        return $this->aclJoinDirector->applyAclRuleOnSelectQueryRelations($query, $aclEntityRuleCollectionTransfer);
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
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleProvider->getCurrentUserAclEntityRules();
        $aclQueryScope = $this->aclQueryScopeResolver->resolve(
            $query,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_UPDATE,
        );

        return $aclQueryScope->applyAclRuleOnUpdateQuery($query, $aclEntityRuleCollectionTransfer);
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
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleProvider->getCurrentUserAclEntityRules();

        if ($this->isSingleRecordQuery($query)) {
            $entity = (clone $query)->findOne();
            $this->aclModelDirector->inspectDelete($entity);

            return $query;
        }

        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            $query->getModelName(),
        );

        if ($aclEntityMetadataTransfer && $aclEntityMetadataTransfer->getIsSubEntity()) {
            throw new FunctionalityNotSupportedException(
                FunctionalityNotSupportedException::SUB_ENTITY_NOT_SUPPORTED_MESSAGE,
            );
        }

        $aclQueryScope = $this->aclQueryScopeResolver->resolve(
            $query,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_DELETE,
        );

        return $aclQueryScope->applyAclRuleOnDeleteQuery($query, $aclEntityRuleCollectionTransfer);
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
        $aclQueryScope = $this->aclQueryScopeResolver->resolve(
            $query,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_READ,
        );

        return $aclQueryScope->applyAclRuleOnSelectQuery($query, $aclEntityRuleCollectionTransfer);
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
        $query = $this->aclQueryExpander->joinSubEntityRootRelation($query, $aclEntityMetadataTransfer);
        $rootAclEntityMetadata = $this->aclEntityMetadataReader->getRootAclEntityMetadataTransferForEntitySubClass(
            $query->getModelName(),
        );

        $rootEntityQuery = PropelQuery::from($rootAclEntityMetadata->getEntityNameOrFail());
        $aclQueryScope = $this->aclQueryScopeResolver->resolve(
            $rootEntityQuery,
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::OPERATION_MASK_READ,
        );

        return $this->aclQueryMerger->mergeQueries(
            $query,
            $aclQueryScope->applyAclRuleOnSelectQuery($rootEntityQuery, $aclEntityRuleCollectionTransfer),
        );
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
        /** @var \Propel\Runtime\Map\ColumnMap $primaryKey */
        $primaryKey = current($query->getTableMap()->getPrimaryKeys());

        return $condition->getColumn() === $primaryKey->getName()
            && $condition->getComparison() === ModelCriteria::EQUAL
            && !is_array($condition->getValue());
    }
}
