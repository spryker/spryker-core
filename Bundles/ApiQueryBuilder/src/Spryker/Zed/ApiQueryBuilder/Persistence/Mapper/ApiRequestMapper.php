<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiQueryBuilder\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderSortTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\ApiQueryBuilder\Dependency\QueryContainer\ApiQueryBuilderToPropelQueryBuilderInterface;
use Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainerInterface;

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
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    public function toPropelQueryBuilderCriteria(ApiRequestTransfer $apiRequestTransfer)
    {
        $apiRequestTransfer->requireFilter();

        $criteriaTransfer = $this->buildPropelQueryBuilderCriteria($apiRequestTransfer);
        $criteriaTransfer = $this->expandResourceCriteria($apiRequestTransfer, $criteriaTransfer);

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function buildPropelQueryBuilderCriteria(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaRuleSet = $this->propelQueryBuilderQueryContainer
            ->createPropelQueryBuilderCriteriaFromJson(
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
    protected function expandResourceCriteria(ApiRequestTransfer $apiRequestTransfer, PropelQueryBuilderCriteriaTransfer $criteriaTransfer)
    {
        $apiRequestTransfer->requireFilter();

        $selectedColumns = $this->buildSelectedColumns(
            $apiRequestTransfer->getFilter()->getFields()
        );

        $paginationTransfer = $this->buildPagination(
            $apiRequestTransfer->getFilter()
        );

        $criteriaTransfer->setPagination($paginationTransfer);
        $criteriaTransfer->setSelectedColumns(new ArrayObject($selectedColumns));

        return $criteriaTransfer;
    }

    /**
     * @param array $selectedColumns
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer[]
     */
    protected function buildSelectedColumns(array $selectedColumns)
    {
        $columns = [];
        foreach ($selectedColumns as $columnAlias) {
            $columnTransfer = new PropelQueryBuilderColumnTransfer();
            $columnTransfer->setAlias($columnAlias);

            $columns[] = $columnTransfer;
        }

        return $columns;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer
     */
    protected function buildPagination(ApiFilterTransfer $apiFilterTransfer)
    {
        $paginationTransfer = new PropelQueryBuilderPaginationTransfer();
        $paginationTransfer->fromArray($apiFilterTransfer->toArray(), true);

        $sortItems = [];
        foreach ($apiFilterTransfer->getSort() as $fieldName => $direction) {
            $sortDirection = Criteria::ASC;
            if (trim($direction) === '-') {
                $sortDirection = Criteria::DESC;
            }

            $columnTransfer = new PropelQueryBuilderColumnTransfer();
            $columnTransfer->setAlias($fieldName);

            $sortItems[] = (new PropelQueryBuilderSortTransfer())
                ->setColumn($columnTransfer)
                ->setSortDirection($sortDirection);
        }

        $paginationTransfer->setSortItems(new ArrayObject($sortItems));

        return $paginationTransfer;
    }

}
