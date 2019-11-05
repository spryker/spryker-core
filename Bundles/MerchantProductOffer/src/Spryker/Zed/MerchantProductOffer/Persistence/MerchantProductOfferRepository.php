<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferPersistenceFactory getFactory()
 */
class MerchantProductOfferRepository extends AbstractRepository implements MerchantProductOfferRepositoryInterface
{
    /**
     * @param string $productOfferReference
     *
     * @return int|null
     */
    public function findIdMerchantByProductOfferReference(string $productOfferReference): ?int
    {
        return $this->getFactory()
            ->createProductOfferPropelQuery()
            ->select(SpyProductOfferTableMap::COL_FK_MERCHANT)
            ->findOne();
    }
}
