<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiPersistenceFactory getFactory()
 */
class ProductOfferGuiRepository extends AbstractRepository implements ProductOfferGuiRepositoryInterface
{
    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed> $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed>
     */
    public function mapQueryCriteriaTransferToModelCriteria(
        SpyProductOfferQuery $query,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): SpyProductOfferQuery {
        return $this->getFactory()
            ->createProductOfferQueryCriteriaMapper()
            ->mapQueryCriteriaTransferToModelCriteria($query, $queryCriteriaTransfer);
    }
}
