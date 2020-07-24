<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiPersistenceFactory getFactory()
 */
class ProductOfferGuiRepository extends AbstractRepository implements ProductOfferGuiRepositoryInterface
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
        return $this->getFactory()
            ->createProductOfferQueryCriteriaMapper()
            ->mapQueryCriteriaTransferToModelCriteria($query, $queryCriteriaTransfer);
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract|null
     */
    public function findProductAbstractBySku(string $sku): ?SpyProductAbstract
    {
        return $this->getFactory()
            ->getProductAbstractPropelQuery()
            ->filterBySku($sku)
            ->findOne();
    }
}
