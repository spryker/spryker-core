<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiQueryBuilder\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderSortTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderTableTransfer;
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
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $tableTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    public function toPropelQueryBuilderCriteria(
        ApiRequestTransfer $apiRequestTransfer,
        PropelQueryBuilderTableTransfer $tableTransfer
    ) {
        $apiRequestTransfer->requireFilter();

        $criteriaTransfer = $this->buildPropelQueryBuilderCriteria($apiRequestTransfer);
        $criteriaTransfer->setTable($tableTransfer);

        $criteriaTransfer = $this->expandResourceCriteria($apiRequestTransfer, $criteriaTransfer);

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $tableTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(ApiRequestTransfer $apiRequestTransfer, ModelCriteria $query, PropelQueryBuilderTableTransfer $tableTransfer)
    {
        $criteriaTransfer = $this->toPropelQueryBuilderCriteria(
            $apiRequestTransfer,
            $tableTransfer
        );

        $query = $this->propelQueryBuilderQueryContainer->createQuery($query, $criteriaTransfer);
        $query->setFormatter(new AssociativeArrayFormatter());

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function buildPropelQueryBuilderCriteria(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaRuleSet = $this->propelQueryBuilderQueryContainer->createPropelQueryBuilderCriteriaFromJson(
            trim($apiRequestTransfer->getFilter()->getCriteriaJson())
        );

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($criteriaRuleSet);

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function expandResourceCriteria(
        ApiRequestTransfer $apiRequestTransfer,
        PropelQueryBuilderCriteriaTransfer $criteriaTransfer
    ) {

        $apiRequestTransfer->requireFilter();

        $columnSelectionTransfer = $this->buildColumnSelection(
            $apiRequestTransfer->getFilter()->getFields(),
            $criteriaTransfer->getTable()
        );

        $paginationTransfer = $this->buildPagination(
            $apiRequestTransfer->getFilter(),
            $criteriaTransfer->getTable()
        );

        $criteriaTransfer->setPagination($paginationTransfer);
        $criteriaTransfer->setColumnSelection($columnSelectionTransfer);

        return $criteriaTransfer;
    }

    /**
     * @param array $selectedColumns
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $tableTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer
     */
    protected function buildColumnSelection(array $selectedColumns, PropelQueryBuilderTableTransfer $tableTransfer)
    {
        $columnSelectionTransfer = new PropelQueryBuilderColumnSelectionTransfer();
        foreach ($selectedColumns as $columnAlias) {
            $columnTransfer = $this->getColumnByAlias($tableTransfer, $columnAlias);
            if ($columnTransfer) {
                $columnSelectionTransfer->addSelectedColumn($columnTransfer);
            }
        }

        return $columnSelectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $tableTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer
     */
    protected function buildPagination(ApiFilterTransfer $apiFilterTransfer, PropelQueryBuilderTableTransfer $tableTransfer)
    {
        $paginationTransfer = new PropelQueryBuilderPaginationTransfer();
        $paginationTransfer->fromArray($apiFilterTransfer->toArray(), true);

        foreach ($apiFilterTransfer->getSort() as $fieldName => $direction) {
            $sortDirection = Criteria::ASC;
            if (trim($direction) === '-') {
                $sortDirection = Criteria::DESC;
            }

            $columnTransfer = $this->getColumnByAlias($tableTransfer, $fieldName);
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
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $tableTransfer
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer|null
     */
    protected function getColumnByAlias(PropelQueryBuilderTableTransfer $tableTransfer, $name)
    {
        foreach ($tableTransfer->getColumns() as $columnTransfer) {
            if (mb_strtolower($name) === mb_strtolower($columnTransfer->getAlias())) {
                return $columnTransfer;
            }
        }

        return null;
    }

}
