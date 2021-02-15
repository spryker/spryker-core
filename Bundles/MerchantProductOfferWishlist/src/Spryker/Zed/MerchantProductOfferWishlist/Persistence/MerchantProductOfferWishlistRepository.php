<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Persistence\MerchantProductOfferWishlistPersistenceFactory getFactory()
 */
class MerchantProductOfferWishlistRepository extends AbstractRepository implements MerchantProductOfferWishlistRepositoryInterface
{
    /**
     * @param string $productOfferReference
     *
     * @return int|null
     */
    public function findMerchantIdByProductOfferReference(string $productOfferReference): ?int
    {
        $productOfferEntity = $this->getFactory()
            ->getProductOfferPropelQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->findOne();

        if (!$productOfferEntity) {
            return null;
        }

        return $productOfferEntity->getFkMerchant();
    }
}
