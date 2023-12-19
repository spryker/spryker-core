<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence;

use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityModelNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityPersistenceFactory getFactory()
 */
class DynamicEntityRepository extends AbstractRepository implements DynamicEntityRepositoryInterface
{
    /**
     * @var string
     */
    protected const TEMPLATE_TABLE_ALIAS_CONDITION = '(table_alias = ? OR parentConfigurationRelation.name IN (%s) OR childConfigurationRelation.name IN (%s))';

    /**
     * @param string $tableAlias
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null
     */
    public function findDynamicEntityConfigurationByTableAlias(string $tableAlias): ?DynamicEntityConfigurationTransfer
    {
        $dynamicEntityConfiguration = $this->getFactory()
            ->createDynamicEntityConfigurationQuery()
            ->filterByTableAlias($tableAlias)
            ->filterByIsActive(true)
            ->findOne();

        if (!$dynamicEntityConfiguration) {
            return null;
        }

        return $this->getFactory()
            ->createDynamicEntityMapper()
            ->mapDynamicEntityConfigurationToTransfer($dynamicEntityConfiguration, new DynamicEntityConfigurationTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationByDynamicEntityCriteria(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer {
        if ($dynamicEntityCriteriaTransfer->getRelationChains() === []) {
            return $this->getDynamicEntityConfigurationTransfersWithoutRelationChains($dynamicEntityCriteriaTransfer);
        }

        $relationChains = $dynamicEntityCriteriaTransfer->getRelationChains();
        $relationNames = $this->getRelationNamesFromRelationChains($relationChains);
        $relationNamesQueryPlaceholders = array_fill(0, count($relationNames), '?');

        $whereParams = $this->buildWhereClauseForRelations(
            $relationNames,
            $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail(),
        );

        $dynamicEntityConfigurationCollection = $this
            ->buildQueryWithRelationAndFieldMapping($relationNamesQueryPlaceholders, $whereParams)
            ->find();

        return $this->getFactory()
            ->createDynamicEntityMapper()
            ->mapDynamicEntityConfigurationsToCollectionTransfer(
                $dynamicEntityConfigurationCollection->getData(),
                new DynamicEntityConfigurationCollectionTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, array<int|string>> $foreignKeyFieldMappingArray
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityModelNotFoundException
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getEntities(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $foreignKeyFieldMappingArray = []
    ): DynamicEntityCollectionTransfer {
        $dynamicEntityCollectionTransfer = new DynamicEntityCollectionTransfer();

        $dynamicEntityQueryClassName = $this->getFactory()->createDynamicEntityQueryBuilder()
            ->getEntityQueryClass($dynamicEntityConfigurationTransfer->getTableNameOrFail());

        if (!class_exists($dynamicEntityQueryClassName)) {
            throw new DynamicEntityModelNotFoundException(
                sprintf(
                    'Model for table "%s" not found.',
                    $dynamicEntityConfigurationTransfer->getTableNameOrFail(),
                ),
            );
        }

        $entityRecordsData = $this->runDynamicEntityQuery(
            $dynamicEntityQueryClassName,
            $dynamicEntityCriteriaTransfer,
            $dynamicEntityConfigurationTransfer,
            $foreignKeyFieldMappingArray,
        );

        if ($entityRecordsData === []) {
            return $dynamicEntityCollectionTransfer;
        }

        return $this->getFactory()->createDynamicEntityMapper()->mapEntityRecordsToCollectionTransfer(
            $entityRecordsData,
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
            $dynamicEntityCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer {
        $dynamicEntityConfigurationsQuery = $this->getFactory()->createDynamicEntityConfigurationQuery();

        $dynamicEntityConfigurationsQuery = $this->applyDynamicEntityConfigurationCriteria($dynamicEntityConfigurationsQuery, $dynamicEntityConfigurationCriteriaTransfer);

        $dynamicEntityConfigurations = $dynamicEntityConfigurationsQuery->find()->getData();

        $dynamicEntityConfigurationCollectionTransfer = new DynamicEntityConfigurationCollectionTransfer();

        if ($dynamicEntityConfigurations === []) {
            return $dynamicEntityConfigurationCollectionTransfer;
        }

        return $this->getFactory()->createDynamicEntityMapper()
            ->mapDynamicEntityConfigurationsToCollectionTransfer($dynamicEntityConfigurations, $dynamicEntityConfigurationCollectionTransfer);
    }

    /**
     * @param array<int, string> $tableNames
     * @param array<int, string> $tableAliases
     *
     * @return array<string, mixed>
     */
    public function findDynamicEntityConfigurationByTableAliasesOrTableNames(array $tableNames = [], array $tableAliases = []): array
    {
        return $this->getFactory()
            ->createDynamicEntityConfigurationQuery()
            ->filterByTableAlias_In($tableAliases)
            ->_or()
            ->filterByTableName_In($tableNames)
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    protected function getDynamicEntityConfigurationTransfersWithoutRelationChains(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer {
        $dynamicEntityConfigurationTransfer = $this->findDynamicEntityConfigurationByTableAlias(
            $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail(),
        );

        if ($dynamicEntityConfigurationTransfer === null) {
            return (new DynamicEntityConfigurationCollectionTransfer());
        }

        return (new DynamicEntityConfigurationCollectionTransfer())
            ->addDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);
    }

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationsQuery
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    protected function applyDynamicEntityConfigurationCriteria(
        SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationsQuery,
        DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
    ): SpyDynamicEntityConfigurationQuery {
        $dynamicEntityConfigurationConditionsTransfer = $dynamicEntityConfigurationCriteriaTransfer->getDynamicEntityConfigurationConditions();

        if ($dynamicEntityConfigurationConditionsTransfer === null) {
            return $dynamicEntityConfigurationsQuery;
        }

        $dynamicEntityConfigurationsQuery = $this->addCreatedAtFilter($dynamicEntityConfigurationsQuery, $dynamicEntityConfigurationConditionsTransfer);
        $dynamicEntityConfigurationsQuery = $this->addUpdatedAtFilter($dynamicEntityConfigurationsQuery, $dynamicEntityConfigurationConditionsTransfer);

        if ($dynamicEntityConfigurationConditionsTransfer->getIsActive() === true) {
            $dynamicEntityConfigurationsQuery->filterByIsActive(true);
        }

        if ($dynamicEntityConfigurationConditionsTransfer->getTableName() !== null) {
            $dynamicEntityConfigurationsQuery->filterByTableName($dynamicEntityConfigurationConditionsTransfer->getTableName());
        }

        return $dynamicEntityConfigurationsQuery;
    }

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationsQuery
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer $dynamicEntityConfigurationConditionsTransfer
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    protected function addCreatedAtFilter(
        SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationsQuery,
        DynamicEntityConfigurationConditionsTransfer $dynamicEntityConfigurationConditionsTransfer
    ): SpyDynamicEntityConfigurationQuery {
        $criteriaRangeFilterTransfer = $dynamicEntityConfigurationConditionsTransfer->getFilterCreatedAt();

        if (!$criteriaRangeFilterTransfer) {
            return $dynamicEntityConfigurationsQuery;
        }

        if ($criteriaRangeFilterTransfer->getFrom()) {
            $dynamicEntityConfigurationsQuery->filterByCreatedAt($criteriaRangeFilterTransfer->getFrom(), Criteria::GREATER_EQUAL);
        }

        if ($criteriaRangeFilterTransfer->getTo()) {
            $dynamicEntityConfigurationsQuery->filterByCreatedAt(
                $criteriaRangeFilterTransfer->getTo(),
                Criteria::LESS_THAN,
            );
        }

        return $dynamicEntityConfigurationsQuery;
    }

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationsQuery
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer $dynamicEntityConfigurationConditionsTransfer
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    protected function addUpdatedAtFilter(
        SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationsQuery,
        DynamicEntityConfigurationConditionsTransfer $dynamicEntityConfigurationConditionsTransfer
    ): SpyDynamicEntityConfigurationQuery {
        $criteriaRangeFilterTransfer = $dynamicEntityConfigurationConditionsTransfer->getFilterUpdatedAt();

        if (!$criteriaRangeFilterTransfer) {
            return $dynamicEntityConfigurationsQuery;
        }

        if ($criteriaRangeFilterTransfer->getFrom()) {
            $dynamicEntityConfigurationsQuery->filterByUpdatedAt($criteriaRangeFilterTransfer->getFrom(), Criteria::GREATER_EQUAL);
        }

        if ($criteriaRangeFilterTransfer->getTo()) {
            $dynamicEntityConfigurationsQuery->filterByUpdatedAt(
                $criteriaRangeFilterTransfer->getTo(),
                Criteria::LESS_THAN,
            );
        }

        return $dynamicEntityConfigurationsQuery;
    }

    /**
     * @param string $dynamicEntityQueryClassName
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, array<int|string>> $foreignKeyFieldMappingArray
     *
     * @return array<mixed>
     */
    protected function runDynamicEntityQuery(
        string $dynamicEntityQueryClassName,
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $foreignKeyFieldMappingArray = []
    ): array {
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $dynamicEntityQuery */
        $dynamicEntityQuery = new $dynamicEntityQueryClassName();

        if ($dynamicEntityCriteriaTransfer->getDynamicEntityConditions() === null && $foreignKeyFieldMappingArray === []) {
            $dynamicEntityQuery->find()->getData();
        }

        $dynamicEntityQuery = $this->getFactory()->createDynamicEntityQueryBuilder()->buildQueryWithFieldConditions(
            $dynamicEntityQuery,
            $dynamicEntityCriteriaTransfer,
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
            $foreignKeyFieldMappingArray,
        );

        $dynamicEntityQuery = $this->buildQueryWithPagination(
            $dynamicEntityQuery,
            $dynamicEntityCriteriaTransfer,
        );

        return $dynamicEntityQuery->find()->getData();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $dynamicEntityQuery
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function buildQueryWithPagination(
        ModelCriteria $dynamicEntityQuery,
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): ModelCriteria {
        if ($dynamicEntityCriteriaTransfer->getPagination()) {
            $dynamicEntityQuery->setLimit($dynamicEntityCriteriaTransfer->getPagination()->getLimitOrFail());
            $dynamicEntityQuery->setOffset($dynamicEntityCriteriaTransfer->getPagination()->getOffset() ?? 0);
        }

        return $dynamicEntityQuery;
    }

    /**
     * @param array<string> $relationNames
     * @param string|null $tableAlias
     *
     * @return array<string>
     */
    protected function buildWhereClauseForRelations(array $relationNames, ?string $tableAlias): array
    {
        $whereParams = $relationNames;
        $whereParams = array_merge($whereParams, $relationNames);

        if ($tableAlias === null) {
            return $whereParams;
        }

        array_unshift($whereParams, $tableAlias);

        return $whereParams;
    }

    /**
     * @param array<string> $relationChains
     *
     * @return array<string>
     */
    protected function getRelationNamesFromRelationChains(array $relationChains): array
    {
        $relationNames = [];
        foreach ($relationChains as $relationChain) {
            $relationNames[] = explode('.', trim($relationChain));
        }

        return array_unique(array_merge(...$relationNames));
    }

    /**
     * @param array<string> $relationNamesQueryPlaceholders
     * @param array<string> $whereParams
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    protected function buildQueryWithRelationAndFieldMapping(
        array $relationNamesQueryPlaceholders,
        array $whereParams
    ): SpyDynamicEntityConfigurationQuery {
        $dynamicEntityConfigurationsQuery = $this->getFactory()->createDynamicEntityConfigurationQuery();

        $relationNamesQueryPlaceholdersString = implode(', ', array_fill(0, count($relationNamesQueryPlaceholders), '?'));

        /** @phpstan-var literal-string $wherePlaceholder */
        $wherePlaceholder = sprintf(static::TEMPLATE_TABLE_ALIAS_CONDITION, $relationNamesQueryPlaceholdersString, $relationNamesQueryPlaceholdersString);

        /** @phpstan-var \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery */
        return $dynamicEntityConfigurationsQuery->filterByIsActive(true)
            ->leftJoinSpyDynamicEntityConfigurationRelationRelatedByFkParentDynamicEntityConfiguration('parentConfigurationRelation')
            ->useSpyDynamicEntityConfigurationRelationRelatedByFkChildDynamicEntityConfigurationQuery('childConfigurationRelation', Criteria::LEFT_JOIN)
            ->leftJoinSpyDynamicEntityConfigurationRelationFieldMapping()
            ->endUse()
            ->where($wherePlaceholder, $whereParams)
            ->with('parentConfigurationRelation')
            ->with('childConfigurationRelation');
    }
}
