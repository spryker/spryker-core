<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Join;

use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ServiceContainer\ServiceContainerInterface;
use Spryker\Zed\AclEntity\Persistence\Exception\SegmentTableJoinNotFoundException;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\DefaultAclQueryScope;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolverInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

abstract class AbstractAclJoin implements AclJoinInterface
{
    /**
     * @var string
     */
    protected const SEGMENT_TABLE_PREFIX = 'spy_acl_entity_segment_';

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolverInterface
     */
    protected $aclQueryScopeResolver;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface
     */
    protected $aclQueryExpander;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface
     */
    protected $aclEntityQueryMerger;

    /**
     * @var \Propel\Runtime\ServiceContainer\ServiceContainerInterface
     */
    protected $propelServiceContainer;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\StrategyResolver\AclQueryScopeResolverInterface $aclQueryScopeResolver
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Expander\AclQueryExpanderInterface $aclQueryExpander
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger\AclEntityQueryMergerInterface $aclEntityQueryMerger
     * @param \Propel\Runtime\ServiceContainer\ServiceContainerInterface $propelServiceContainer
     */
    public function __construct(
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        AclQueryScopeResolverInterface $aclQueryScopeResolver,
        AclQueryExpanderInterface $aclQueryExpander,
        AclEntityQueryMergerInterface $aclEntityQueryMerger,
        ServiceContainerInterface $propelServiceContainer
    ) {
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->aclQueryScopeResolver = $aclQueryScopeResolver;
        $this->aclQueryExpander = $aclQueryExpander;
        $this->aclEntityQueryMerger = $aclEntityQueryMerger;
        $this->propelServiceContainer = $propelServiceContainer;
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function getModelClass(string $tableName): string
    {
        $class = $this->propelServiceContainer->getDatabaseMap()->getTable($tableName)->getClassName();

        return strpos($class, '\\') === 0 ? substr($class, 1) : $class;
    }

    /**
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param string $class
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function getQuery(string $class): ModelCriteria
    {
        return PropelQuery::from($class);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    protected function isSubEntity(string $class): bool
    {
        $aclEntityMetadata = $this->aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass($class);

        return $aclEntityMetadata && $aclEntityMetadata->getIsSubEntity();
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param string $subEntityClass
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function joinSubEntityRoot(
        ModelCriteria $query,
        string $subEntityClass,
        string $joinType = Criteria::INNER_JOIN
    ): ModelCriteria {
        if (!$this->isSubEntity($subEntityClass)) {
            return $query;
        }

        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass($subEntityClass);

        return $this->aclQueryExpander->joinSubEntityRootRelation($query, $aclEntityMetadataTransfer, $joinType);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function forbidJoin(ModelCriteria $query, Join $join): ModelCriteria
    {
        return $query->addJoinCondition($this->getJoinKey($query, $join), '0=1');
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    protected function hasSegmentJoin(ModelCriteria $query): bool
    {
        foreach ($query->getJoins() as $join) {
            $rightTableName = $join->getRightTableName() ?: '';
            if (strpos($rightTableName, static::SEGMENT_TABLE_PREFIX) === 0) {
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
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\SegmentTableJoinNotFoundException
     *
     * @return \Propel\Runtime\ActiveQuery\Join
     */
    protected function getAclEntitySegmentJoin(ModelCriteria $query): Join
    {
        foreach ($query->getJoins() as $join) {
            /** @var string $rightTableName */
            $rightTableName = $join->getRightTableName();
            if (strpos($rightTableName, static::SEGMENT_TABLE_PREFIX) === 0) {
                return $join;
            }
        }

        throw new SegmentTableJoinNotFoundException();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return bool
     */
    protected function isAclEntitySegmentTableJoin(Join $join): bool
    {
        /** @var string $rightTableName */
        $rightTableName = $join->getRightTableName();

        return strpos($rightTableName, static::SEGMENT_TABLE_PREFIX) === 0;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return bool
     */
    protected function isPivotTableJoin(Join $join): bool
    {
        /** @var string $rightTableName */
        $rightTableName = $join->getRightTableName();
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->findAclEntityMetadataTransferByConnectionClass(
            $this->getModelClass($rightTableName),
        );

        return $aclEntityMetadataTransfer !== null;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query\AclQueryScopeInterface $aclQueryScope
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    protected function isReadableQuery(AclQueryScopeInterface $aclQueryScope, ModelCriteria $query): bool
    {
        if (!$aclQueryScope instanceof DefaultAclQueryScope) {
            return true;
        }

        return $aclQueryScope->isReadableQuery($query);
    }

    /**
     * @param string $table
     *
     * @return array<\Propel\Runtime\Map\ColumnMap>
     */
    protected function getPrimaryKeys(string $table): array
    {
        return $this->propelServiceContainer->getDatabaseMap()->getTable($table)->getPrimaryKeys();
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return string
     */
    protected function getJoinKey(ModelCriteria $query, Join $join): string
    {
        foreach ($query->getJoins() as $name => $relation) {
            /** @var \Propel\Runtime\Adapter\SqlAdapterInterface|null $adapter */
            $adapter = $relation->getAdapter();
            if (!$adapter) {
                $relation->setAdapter($this->propelServiceContainer->getAdapter());
            }
            if ($relation->equals($join)) {
                return $name;
            }
        }

        return '';
    }
}
