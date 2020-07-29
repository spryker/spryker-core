<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence\Mapper;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class ProductOfferQueryCriteriaMapper implements ProductOfferQueryCriteriaMapperInterface
{
    /**
     * @phpstan-param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed> $query
     *
     * @phpstan-return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed>
     *
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function mapQueryCriteriaTransferToModelCriteria(
        SpyProductOfferQuery $query,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): SpyProductOfferQuery {
        $query = $this->addJoin($query, $queryCriteriaTransfer);
        $query = $this->addWithColumns($query, $queryCriteriaTransfer);

        return $query;
    }

    /**
     * @phpstan-param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed> $query
     *
     * @phpstan-return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed>
     *
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addJoin(
        SpyProductOfferQuery $query,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): SpyProductOfferQuery {
        foreach ($queryCriteriaTransfer->getJoins() as $queryJoinTransfer) {
            $joinType = $queryJoinTransfer->getJoinType() ?? Criteria::INNER_JOIN;
            if ($queryJoinTransfer->getRelation()) {
                $query->join($queryJoinTransfer->getRelation(), $joinType);

                if ($queryJoinTransfer->getCondition()) {
                    $query->addJoinCondition($queryJoinTransfer->getRelation(), $queryJoinTransfer->getCondition());
                }

                continue;
            }
            $query->addJoin($queryJoinTransfer->getLeft(), $queryJoinTransfer->getRight(), $joinType);
        }

        return $query;
    }

    /**
     * @phpstan-param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed> $query
     *
     * @phpstan-return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed>
     *
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addWithColumns(
        SpyProductOfferQuery $query,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): SpyProductOfferQuery {
        foreach ($queryCriteriaTransfer->getWithColumns() as $field => $value) {
            $query->withColumn($field, $value);
        }

        return $query;
    }
}
