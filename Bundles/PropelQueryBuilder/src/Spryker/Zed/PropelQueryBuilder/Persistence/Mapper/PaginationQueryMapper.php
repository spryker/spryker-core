<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\Mapper;

use Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainer getQueryContainer()
 */
class PaginationQueryMapper implements PaginationQueryMapperInterface
{

    /**
     * @var int
     */
    protected $defaultItemsPerPage = 20;

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer|null $propelQueryBuilderPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapPagination(ModelCriteria $query, PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer)
    {
        $propelQueryBuilderPaginationTransfer = $this->updatePaginationTotals($query, $propelQueryBuilderPaginationTransfer);

        $query = $this->mapQueryLimit($query, $propelQueryBuilderPaginationTransfer);
        $query = $this->mapQueryOffset($query, $propelQueryBuilderPaginationTransfer);
        $query = $this->mapQuerySort($query, $propelQueryBuilderPaginationTransfer);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapQueryLimit(ModelCriteria $query, PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer)
    {
        $propelQueryBuilderPaginationTransfer = $this->ensurePaginationDefaultValues($propelQueryBuilderPaginationTransfer);
        $query->setLimit($propelQueryBuilderPaginationTransfer->getItemsPerPage());

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapQueryOffset(ModelCriteria $query, PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer)
    {
        $propelQueryBuilderPaginationTransfer = $this->ensurePaginationDefaultValues($propelQueryBuilderPaginationTransfer);

        $itemsPerPage = (int)$propelQueryBuilderPaginationTransfer->getItemsPerPage();
        $page = (int)$propelQueryBuilderPaginationTransfer->getPage();

        if ($page <= 0) {
            $page = 1;
        }

        $offset = ($page - 1) * $itemsPerPage;

        $query->setOffset($offset);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapQuerySort(ModelCriteria $query, PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer)
    {
        $sortCollection = $propelQueryBuilderPaginationTransfer->getSortItems();

        foreach ($sortCollection as $sortItem) {
            $columnTransfer = $sortItem->getColumn();
            $columnTransfer->requireName();

            if (strtolower($sortItem->getSortDirection()) === strtolower(Criteria::ASC)) {
                $query->addAscendingOrderByColumn($columnTransfer->getName());
            } else {
                $query->addDescendingOrderByColumn($columnTransfer->getName());
            }
        }

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer
     */
    protected function updatePaginationTotals(ModelCriteria $query, PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer)
    {
        $propelQueryBuilderPaginationTransfer = $this->ensurePaginationDefaultValues($propelQueryBuilderPaginationTransfer);

        if ($propelQueryBuilderPaginationTransfer->getTotal() === null || $propelQueryBuilderPaginationTransfer->getPageTotal() === null) {
            $total = $query->count();
            $pageTotal = 0;
            if ($total > 0) {
                $pageTotal = ceil($total / $propelQueryBuilderPaginationTransfer->getItemsPerPage());
            }

            $propelQueryBuilderPaginationTransfer->setTotal($total);
            $propelQueryBuilderPaginationTransfer->setPageTotal($pageTotal);
        }

        return $propelQueryBuilderPaginationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer
     */
    protected function ensurePaginationDefaultValues(PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer)
    {
        if (!$propelQueryBuilderPaginationTransfer->getPage()) {
            $propelQueryBuilderPaginationTransfer->setPage(1);
        }

        if (!$propelQueryBuilderPaginationTransfer->getItemsPerPage()) {
            $propelQueryBuilderPaginationTransfer->setItemsPerPage($this->defaultItemsPerPage);
        }

        return $propelQueryBuilderPaginationTransfer;
    }

}
