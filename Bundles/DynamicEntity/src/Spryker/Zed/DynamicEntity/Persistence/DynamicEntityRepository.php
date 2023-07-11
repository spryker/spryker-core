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
    protected const QUERY_CLASS_PLACEHOLDER = '%sQuery';

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
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityModelNotFoundException
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getEntities(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
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

        $entityRecordsData = $this->runDynamicEntityQuery($dynamicEntityQueryClassName, $dynamicEntityCriteriaTransfer, $dynamicEntityConfigurationTransfer);

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

        if ($dynamicEntityConfigurationCriteriaTransfer->getDynamicEntityConfigurationConditions() !== null) {
            $dynamicEntityConfigurationsQuery = $this->filterConfigurationByConditions($dynamicEntityConfigurationsQuery, $dynamicEntityConfigurationCriteriaTransfer->getDynamicEntityConfigurationConditions());
        }

        $dynamicEntityConfigurations = $dynamicEntityConfigurationsQuery->find()->getData();

        $dynamicEntityConfigurationCollectionTransfer = new DynamicEntityConfigurationCollectionTransfer();

        if ($dynamicEntityConfigurations === []) {
            return $dynamicEntityConfigurationCollectionTransfer;
        }

        return $this->getFactory()->createDynamicEntityMapper()
            ->mapDynamicEntityConfigurationsToCollectionTransfer($dynamicEntityConfigurations, $dynamicEntityConfigurationCollectionTransfer);
    }

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationsQuery
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer $dynamicEntityConfigurationConditionsTransfer
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    protected function filterConfigurationByConditions(
        SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationsQuery,
        DynamicEntityConfigurationConditionsTransfer $dynamicEntityConfigurationConditionsTransfer
    ): SpyDynamicEntityConfigurationQuery {
        if ($dynamicEntityConfigurationConditionsTransfer->getIsActive() === true) {
            $dynamicEntityConfigurationsQuery->filterByIsActive(true);
        }

        return $dynamicEntityConfigurationsQuery;
    }

    /**
     * @param string $dynamicEntityQueryClassName
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function runDynamicEntityQuery(
        string $dynamicEntityQueryClassName,
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array {
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $dynamicEntityQuery */
        $dynamicEntityQuery = new $dynamicEntityQueryClassName();

        $dynamicEntityQuery = $this->getFactory()->createDynamicEntityQueryBuilder()->buildQueryWithFieldConditions(
            $dynamicEntityQuery,
            $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail(),
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
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
}
