<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedPaginationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

abstract class QueryBuilderAbstract implements QueryBuilderInterface
{

    /**
     * @param ModelCriteria $entityQuery
     * @param DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return void
     */
    protected function applyPagination(
        ModelCriteria $entityQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer
    ) {
        $this->setQueryLimit($entityQuery, $dataFeedPaginationTransfer);
        $this->setQueryOffset($entityQuery, $dataFeedPaginationTransfer);
    }

    /**
     * @param ModelCriteria $entityQuery
     * @param DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return void
     */
    protected function setQueryLimit(
        ModelCriteria $entityQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer
    ) {
        if ($dataFeedPaginationTransfer->getLimit()) {
            $entityQuery
                ->setLimit($dataFeedPaginationTransfer->getLimit());
        }
    }

    /**
     * @param ModelCriteria $entityQuery
     * @param DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return void
     */
    protected function setQueryOffset(
        ModelCriteria $entityQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer
    ) {
        if ($dataFeedPaginationTransfer->getOffset()) {
            $entityQuery
                ->setOffset($dataFeedPaginationTransfer->getOffset());
        }
    }

}
