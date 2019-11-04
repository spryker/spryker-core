<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

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
    public function findIdMerchantByOfferReference(string $productOfferReference): ?int
    {
        $productOfferEntity = $this->getFactory()->createProductOfferQuery()
            ->findOneByProductOfferReference($productOfferReference);

        if (!$productOfferEntity || !$productOfferEntity->getFkMerchant()) {
            return null;
        }

        return $productOfferEntity->getFkMerchant();
    }
}
