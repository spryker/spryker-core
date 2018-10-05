<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiQueryBuilder\Persistence\Mapper;

use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderSortTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\ApiQueryBuilder\Dependency\QueryContainer\ApiQueryBuilderToPropelQueryBuilderInterface;
use Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\AssociativeArrayFormatter;

class ApiRequestMapper implements ApiRequestMapperInterface
{
    /**
     * @var \Spryker\Zed\ApiQueryBuilder\Dependency\QueryContainer\ApiQueryBuilderToPropelQueryBuilderInterface
     */
    protected $propelQueryBuilderQueryContainer;

    /**
     * @var \Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ApiQueryBuilder\Dependency\QueryContainer\ApiQueryBuilderToPropelQueryBuilderInterface $propelQueryBuilderQueryContainer
     * @param \Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainerInterface $queryContainer
     */
    public function __construct(
        ApiQueryBuilderToPropelQueryBuilderInterface $propelQueryBuilderQueryContainer,
        ApiQueryBuilderQueryContainerInterface $queryContainer
    ) {
        $this->propelQueryBuilderQueryContainer = $propelQueryBuilderQueryContainer;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    public function toPropelQueryBuilderCriteria(ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer)
    {
        $apiQueryBuilderQueryTransfer->requireApiRequest();
        $apiQueryBuilderQueryTransfer->getApiRequest()->requireFilter();

        $criteriaTransfer = $this->buildPropelQueryBuilderCriteria($apiQueryBuilderQueryTransfer);
        $criteriaTransfer = $this->expandResourceCriteria($apiQueryBuilderQueryTransfer->getApiRequest(), $criteriaTransfer);

        return $criteriaTransfer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(ModelCriteria $query, ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer)
    {
        $criteriaTransfer = $this->toPropelQueryBuilderCriteria($apiQueryBuilderQueryTransfer);

        $query = $this->propelQueryBuilderQueryContainer->createQuery($query, $criteriaTransfer);
        $query->setFormatter(new AssociativeArrayFormatter());

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function buildPropelQueryBuilderCriteria(ApiQueryBuilderQueryTransfer $apiQueryBuilderQueryTransfer)
    {
        $apiQueryBuilderQueryTransfer->requireApiRequest();
        $apiQueryBuilderQueryTransfer->getApiRequest()->requireFilter();

        $criteriaRuleSet = $this->propelQueryBuilderQueryContainer->createPropelQueryBuilderCriteriaFromJson(
            $apiQueryBuilderQueryTransfer->getApiRequest()->getFilter()->getCriteriaJson()
        );

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($criteriaRuleSet);
        $criteriaTransfer->setColumnSelection($apiQueryBuilderQueryTransfer->getColumnSelection());

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function expandResourceCriteria(ApiRequestTransfer $apiRequestTransfer, PropelQueryBuilderCriteriaTransfer $criteriaTransfer)
    {
        $apiRequestTransfer->requireFilter();

        $columnSelectionTransfer = $this->buildColumnSelection(
            $apiRequestTransfer->getFilter()->getFields(),
            $criteriaTransfer->getColumnSelection()
        );

        $paginationTransfer = $this->buildPagination(
            $apiRequestTransfer->getFilter(),
            $criteriaTransfer->getColumnSelection()
        );

        $criteriaTransfer->setPagination($paginationTransfer);
        $criteriaTransfer->setColumnSelection($columnSelectionTransfer);

        return $criteriaTransfer;
    }

    /**
     * @param array $selectedColumns
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer
     */
    protected function buildColumnSelection(array $selectedColumns, PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer)
    {
        foreach ($selectedColumns as $selectedColumnAlias) {
            $columnTransfer = $this->getColumnByAlias((array)$columnSelectionTransfer->getTableColumns(), $selectedColumnAlias);
            if ($columnTransfer) {
                $columnSelectionTransfer->addSelectedColumn($columnTransfer);
            }
        }

        return $columnSelectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer
     */
    protected function buildPagination(ApiFilterTransfer $apiFilterTransfer, PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer)
    {
        $paginationTransfer = new PropelQueryBuilderPaginationTransfer();
        $paginationTransfer->fromArray($apiFilterTransfer->toArray(), true);

        foreach ($apiFilterTransfer->getSort() as $columnAlias => $direction) {
            $sortDirection = Criteria::ASC;
            if (trim($direction) === '-') {
                $sortDirection = Criteria::DESC;
            }

            $columnTransfer = $this->getColumnByAlias((array)$columnSelectionTransfer->getTableColumns(), $columnAlias);
            if ($columnTransfer) {
                $sortItemTransfer = (new PropelQueryBuilderSortTransfer())
                    ->setColumn($columnTransfer)
                    ->setSortDirection($sortDirection);

                $paginationTransfer->addSortItem($sortItemTransfer);
            }
        }

        return $paginationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer[] $columnCollection
     * @param string $columnAlias
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer|null
     */
    protected function getColumnByAlias(array $columnCollection, $columnAlias)
    {
        foreach ($columnCollection as $columnTransfer) {
            if (mb_strtolower($columnAlias) === mb_strtolower($columnTransfer->getAlias())) {
                return $columnTransfer;
            }
        }

        return null;
    }
}
