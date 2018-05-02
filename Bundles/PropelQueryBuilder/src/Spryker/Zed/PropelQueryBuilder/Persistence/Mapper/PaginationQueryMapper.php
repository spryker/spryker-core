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
 * @method \Spryker\Zed\PropelQueryBuilder\PropelQueryBuilderConfig getConfig()
 * @method \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainerInterface getQueryContainer()
 */
class PaginationQueryMapper implements PaginationQueryMapperInterface
{
    /**
     * @var int
     */
    protected $defaultItemsPerPage = 20;

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapPagination(
        ModelCriteria $query,
        PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
    ) {
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
    protected function mapQueryLimit(
        ModelCriteria $query,
        PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
    ) {
        if ($propelQueryBuilderPaginationTransfer->getLimit() || $propelQueryBuilderPaginationTransfer->getOffset()) {
            $query->setLimit($propelQueryBuilderPaginationTransfer->getLimit());

            return $query;
        }

        $query->setLimit($this->getItemsPerPage($propelQueryBuilderPaginationTransfer));

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapQueryOffset(
        ModelCriteria $query,
        PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
    ) {
        if ($propelQueryBuilderPaginationTransfer->getOffset() || $propelQueryBuilderPaginationTransfer->getOffset()) {
            $query->setOffset($propelQueryBuilderPaginationTransfer->getOffset());

            return $query;
        }

        $itemsPerPage = $this->getItemsPerPage($propelQueryBuilderPaginationTransfer);
        $page = (int)$propelQueryBuilderPaginationTransfer->getPage();

        if ($page < 0) {
            $page = 0;
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
    protected function mapQuerySort(
        ModelCriteria $query,
        PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
    ) {
        $sortCollection = $propelQueryBuilderPaginationTransfer->getSortItems();

        foreach ($sortCollection as $sortItem) {
            $sortItem->requireColumn();
            $sortItem->getColumn()->requireName();

            if (strtolower($sortItem->getSortDirection()) === strtolower(Criteria::ASC)) {
                $query->addAscendingOrderByColumn($sortItem->getColumn()->getName());
            } else {
                $query->addDescendingOrderByColumn($sortItem->getColumn()->getName());
            }
        }

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer
     *
     * @return int
     */
    protected function getItemsPerPage(PropelQueryBuilderPaginationTransfer $propelQueryBuilderPaginationTransfer)
    {
        $itemsPerPage = (int)$propelQueryBuilderPaginationTransfer->getItemsPerPage();
        if (!$itemsPerPage) {
            $itemsPerPage = $this->defaultItemsPerPage;
        }

        return $itemsPerPage;
    }
}
