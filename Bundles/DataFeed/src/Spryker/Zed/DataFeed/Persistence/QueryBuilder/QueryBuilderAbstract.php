<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedDateFilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

abstract class QueryBuilderAbstract implements QueryBuilderInterface
{

    const LOCALE_FILTER_VALUE = 'LOCALE_FILTER_VALUE';
    const LOCALE_FILTER_CRITERIA = 'LOCALE_FILTER_CRITERIA';
    const JOIN_TOUCH_TABLE_JOIN_NAME = 'JOIN_TOUCH_TABLE_JOIN_NAME';

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

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $entityQuery
     * @param string $primaryKeyColumnName
     * @param string $touchItemType
     *
     * @return ModelCriteria
     */
    protected function joinTouchTable(ModelCriteria $entityQuery, $primaryKeyColumnName, $touchItemType)
    {
        $entityQuery->addJoin(
            $primaryKeyColumnName,
            SpyTouchTableMap::COL_ITEM_ID,
            Criteria::INNER_JOIN
        );
        $entityQuery->condition(
            self::JOIN_TOUCH_TABLE_JOIN_NAME,
            SpyTouchTableMap::COL_ITEM_TYPE . '= ?',
            $touchItemType,
            \PDO::PARAM_STR
        );
        $entityQuery->where([self::JOIN_TOUCH_TABLE_JOIN_NAME]);

        return $entityQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $entityQuery
     * @param \Generated\Shared\Transfer\DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
     * @param string $primaryKeyColumnName
     * @param string $touchItemType
     *
     * @return ModelCriteria
     */
    protected function applyDateFilter(
        ModelCriteria $entityQuery,
        DataFeedDateFilterTransfer $dataFeedDateFilterTransfer = null,
        $primaryKeyColumnName,
        $touchItemType
    ) {
        if ($dataFeedDateFilterTransfer !== null) {
            $entityQuery = $this->joinTouchTable(
                $entityQuery,
                $primaryKeyColumnName,
                $touchItemType
            );

            if ($dataFeedDateFilterTransfer->getUpdatedFrom() !== null) {
                $entityQuery->condition(
                    'updatedFromCondition',
                    SpyTouchTableMap::COL_TOUCHED . '> ?',
                    $dataFeedDateFilterTransfer->getUpdatedFrom(),
                    \PDO::PARAM_STR
                );
                $entityQuery->where(['updatedFromCondition']);
            }

            if ($dataFeedDateFilterTransfer->getUpdatedTo() !== null) {
                $entityQuery->condition(
                    'updatedToCondition',
                    SpyTouchTableMap::COL_TOUCHED . '< ?',
                    $dataFeedDateFilterTransfer->getUpdatedTo(),
                    \PDO::PARAM_STR
                );
                $entityQuery->where(['updatedToCondition']);
            }
        }

        return $entityQuery;
    }

}
