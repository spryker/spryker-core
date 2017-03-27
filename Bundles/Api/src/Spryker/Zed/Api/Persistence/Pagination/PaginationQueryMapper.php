<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence\Pagination;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainer getQueryContainer()
 */
class PaginationQueryMapper implements PaginationQueryMapperInterface
{

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapPagination(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        $query = $this->mapQueryLimit($query, $apiPaginationTransfer);
        $query = $this->mapQueryOffset($query, $apiPaginationTransfer);
        $query = $this->mapQuerySort($query, $apiPaginationTransfer);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapQueryLimit(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        $limit = (int)$apiPaginationTransfer->getLimit();
        $query->setLimit($limit);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapQueryOffset(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        $page = (int)$apiPaginationTransfer->getPage();
        $limit = (int)$apiPaginationTransfer->getLimit();

        $offset = ($page - 1) * $limit;
        if ($offset < 0) {
            $offset = 0;
        }

        $query->setOffset($offset);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapQuerySort(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        $sortCollection = (array)$apiPaginationTransfer->getSort();

        foreach ($sortCollection as $column => $order) {
            if ($order === Criteria::ASC) {
                $query->addAscendingOrderByColumn($column);
            } else {
                $query->addDescendingOrderByColumn($column);
            }
        }

        return $query;
    }

}
