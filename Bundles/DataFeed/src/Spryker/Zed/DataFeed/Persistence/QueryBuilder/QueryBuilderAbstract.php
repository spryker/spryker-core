<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedPaginationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

abstract class QueryBuilderAbstract implements QueryBuilderInterface
{

    const LOCALE_FILTER_VALUE = 'LOCALE_FILTER_VALUE';

    const LOCALE_FILTER_CRITERIA = 'LOCALE_FILTER_CRITERIA';

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $entityQuery
     * @param \Generated\Shared\Transfer\DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return ModelCriteria
     */
    protected function applyPagination(
        ModelCriteria $entityQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer = null
    ) {
        if ($dataFeedPaginationTransfer !== null) {
            $entityQuery = $this->setQueryLimit($entityQuery, $dataFeedPaginationTransfer);
            $entityQuery = $this->setQueryOffset($entityQuery, $dataFeedPaginationTransfer);
        }

        return $entityQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $entityQuery
     * @param \Generated\Shared\Transfer\DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return ModelCriteria
     */
    protected function setQueryLimit(
        ModelCriteria $entityQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer
    ) {
        if ($dataFeedPaginationTransfer->getLimit()) {
            $entityQuery
                ->setLimit($dataFeedPaginationTransfer->getLimit());
        }

        return $entityQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $entityQuery
     * @param \Generated\Shared\Transfer\DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return ModelCriteria
     */
    protected function setQueryOffset(
        ModelCriteria $entityQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer
    ) {
        if ($dataFeedPaginationTransfer->getOffset()) {
            $entityQuery
                ->setOffset($dataFeedPaginationTransfer->getOffset());
        }

        return $entityQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getIdLocaleFilterConditions(LocaleTransfer $localeTransfer = null)
    {
        if ($localeTransfer !== null && $localeTransfer->getIdLocale() !== null) {
            $filterCriteria = Criteria::EQUAL;
            $filterValue = $localeTransfer->getIdLocale();
        } else {
            $filterCriteria = Criteria::NOT_EQUAL;
            $filterValue = null;
        }

        return [
            self::LOCALE_FILTER_VALUE => $filterValue,
            self::LOCALE_FILTER_CRITERIA => $filterCriteria,
        ];
    }

}
