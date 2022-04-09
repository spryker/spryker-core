<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Expander\Strategy;

use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\ServiceContainerInterface;
use Spryker\Zed\AclEntity\Persistence\Exception\JoinNotFoundException;
use Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException;
use Spryker\Zed\AclEntity\Persistence\Exception\RelationNotFoundException;
use Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparatorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGeneratorInterface;

abstract class AbstractAclEntityConnection
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparatorInterface
     */
    protected $joinComparator;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGeneratorInterface
     */
    protected $queryAliasGenerator;

    /**
     * @var \Propel\Runtime\ServiceContainer\ServiceContainerInterface
     */
    protected $propelServiceContainer;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparatorInterface $joinComparator
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGeneratorInterface $queryAliasGenerator
     * @param \Propel\Runtime\ServiceContainer\ServiceContainerInterface $propelServiceContainer
     */
    public function __construct(
        JoinComparatorInterface $joinComparator,
        AclEntityAliasGeneratorInterface $queryAliasGenerator,
        ServiceContainerInterface $propelServiceContainer
    ) {
        $this->joinComparator = $joinComparator;
        $this->queryAliasGenerator = $queryAliasGenerator;
        $this->propelServiceContainer = $propelServiceContainer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\Join
     */
    abstract protected function generateAclEntityJoin(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        string $joinType
    ): Join;

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     * @param string $joinType
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    public function joinRelation(
        ModelCriteria $query,
        AclEntityMetadataTransfer $aclEntityMetadataTransfer,
        string $joinType = Criteria::INNER_JOIN
    ): ModelCriteria {
        if ($query->getModelName() === $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail()) {
            return $query;
        }
        $aclEntityJoin = $this->generateAclEntityJoin($query, $aclEntityMetadataTransfer, $joinType);
        $parentRelationName = $this->getParentRelationName($aclEntityMetadataTransfer);
        $parentTableName = $this->getTableMapByEntityClass(
            $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
        )->getNameOrFail();
        if (!$this->hasTableNameOrAlias($query, $parentTableName)) {
            return $query->addJoinObject($aclEntityJoin, $parentRelationName);
        }

        $queryJoin = $this->getQueryJoinByTableName($query, $parentTableName);
        if ($this->joinComparator->areEqual($queryJoin, $aclEntityJoin)) {
            return $query;
        }

        $query = $this->extendQueryAliases($query, $aclEntityJoin);

        return $query->addJoinObject(
            $aclEntityJoin,
            $this->queryAliasGenerator->generateJoinAlias(
                $query,
                $this->getShortClassName($aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail()),
            ),
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Propel\Runtime\ActiveQuery\Join $aclEntityJoin
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function extendQueryAliases(ModelCriteria $query, Join $aclEntityJoin): ModelCriteria
    {
        $rightTableAlias = $aclEntityJoin->getRightTableAlias();
        $rightTableName = $aclEntityJoin->getRightTableName();
        if ($rightTableAlias && $rightTableName) {
            $query->addAlias($rightTableAlias, $rightTableName);
        }

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @return string
     */
    protected function getParentRelationName(AclEntityMetadataTransfer $aclEntityMetadataTransfer): string
    {
        $relationName = $this->getShortClassName(
            $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail(),
        );
        $tableMap = $this->getTableMapByEntityClass($aclEntityMetadataTransfer->getEntityNameOrFail());
        if ($tableMap->hasRelation($relationName)) {
            return $relationName;
        }

        foreach ($tableMap->getRelations() as $relationMap) {
            if ($relationMap->getRightTable()->getPhpName() === $relationName) {
                return $relationMap->getName();
            }
        }

        return $relationName;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param string $tableName
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\JoinNotFoundException
     *
     * @return \Propel\Runtime\ActiveQuery\Join
     */
    protected function getQueryJoinByTableName(ModelCriteria $query, string $tableName): Join
    {
        foreach ($query->getJoins() as $join) {
            if ($join->getRightTableName() === $tableName) {
                return $join;
            }
        }

        throw new JoinNotFoundException($query, $tableName);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param string $alias
     *
     * @return bool
     */
    protected function hasTableNameOrAlias(ModelCriteria $query, string $alias): bool
    {
        if (in_array($alias, $query->getAliases())) {
            return true;
        }

        foreach ($query->getJoins() as $join) {
            $joinAliases = $this->getJoinTableNamesAndAliases($join);
            if (in_array($alias, $joinAliases)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return array<string>
     */
    protected function getJoinTableNamesAndAliases(Join $join): array
    {
        return array_merge($this->getJoinTableNames($join), $this->getJoinTableAliases($join));
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return array<string>
     */
    protected function getJoinTableAliases(Join $join): array
    {
        $aliases = [];
        $leftTableAlias = $join->getLeftTableAlias();
        if ($leftTableAlias) {
            $aliases[] = $leftTableAlias;
        }
        $rightTableAlias = $join->getRightTableAlias();
        if ($rightTableAlias) {
            $aliases[] = $rightTableAlias;
        }

        return $aliases;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return array<string>
     */
    protected function getJoinTableNames(Join $join): array
    {
        /** @phpstan-var array<string> */
        return array_filter(
            [$join->getLeftTableName(), $join->getRightTableName()],
            function (?string $tableName) {
                return (bool)$tableName;
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataTransfer $aclEntityMetadataTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\RelationNotFoundException
     *
     * @return \Propel\Runtime\Map\RelationMap
     */
    protected function getRelationMap(AclEntityMetadataTransfer $aclEntityMetadataTransfer): RelationMap
    {
        $entityClass = $aclEntityMetadataTransfer->getEntityNameOrFail();
        $parentClass = $aclEntityMetadataTransfer->getParentOrFail()->getEntityNameOrFail();

        $relation = $this->findRelation($entityClass, $parentClass);
        if ($relation) {
            return $relation;
        }

        $relation = $this->findRelation($parentClass, $entityClass);
        if ($relation) {
            return $relation;
        }

        throw new RelationNotFoundException($entityClass, $parentClass);
    }

    /**
     * @param string $class
     * @param string $relationClass
     *
     * @return \Propel\Runtime\Map\RelationMap|null
     */
    protected function findRelation(string $class, string $relationClass): ?RelationMap
    {
        $relations = Propel::getServiceContainer()->getDatabaseMap()->getTableByPhpName($class)->getRelations();
        foreach ($relations as $relation) {
            $rightTableClass = ltrim($relation->getRightTable()->getClassNameOrFail(), '\\');
            if ($rightTableClass === $relationClass) {
                return $relation;
            }
        }

        return null;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException
     *
     * @return \Propel\Runtime\ActiveQuery\Join
     */
    protected function updateJoinAliases(ModelCriteria $query, Join $join): Join
    {
        $rightTableName = $join->getRightTableName();
        $leftTableName = $join->getLeftTableName();
        if (!$leftTableName || !$rightTableName) {
            throw new QueryMergerJoinMalfunctionException();
        }

        if ($this->hasTableNameOrAlias($query, $rightTableName)) {
            $rightTableAclEntityAlias = $this->queryAliasGenerator->generateTableAlias($query, $rightTableName);
            $join->setRightTableAlias($rightTableAclEntityAlias);
        }

        $leftTableAclEntityAlias = $this->queryAliasGenerator->generateTableAlias($query, $leftTableName);
        if ($this->hasTableNameOrAlias($query, $leftTableAclEntityAlias)) {
            $join->setLeftTableAlias($leftTableAclEntityAlias);
        }

        return $join;
    }

    /**
     * @param string $relatedClass
     *
     * @return string
     */
    protected function getShortClassName(string $relatedClass): string
    {
        return basename(str_replace('\\', '/', $relatedClass));
    }

    /**
     * @param string $entityClass
     *
     * @return \Propel\Runtime\Map\TableMap
     */
    protected function getTableMapByEntityClass(string $entityClass): TableMap
    {
        return $this->propelServiceContainer->getDatabaseMap()->getTableByPhpName($entityClass);
    }
}
