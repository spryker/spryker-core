<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence\Mapper;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;

interface ProductOfferQueryCriteriaMapperInterface
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
    ): SpyProductOfferQuery;
}
