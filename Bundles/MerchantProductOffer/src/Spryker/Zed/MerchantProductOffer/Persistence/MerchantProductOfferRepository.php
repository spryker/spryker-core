<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferPersistenceFactory getFactory()
 */
class MerchantProductOfferRepository extends AbstractRepository implements MerchantProductOfferRepositoryInterface
{
    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findMerchantProductOfferByOfferReference(string $productOfferReference): ?ProductOfferTransfer
    {
        $productOfferEntity = $this->getFactory()->createProductOfferQuery()
            ->findOneByProductOfferReference($productOfferReference);

        if (!$productOfferEntity) {
            return null;
        }

        return $this->getFactory()->createMerchantProductOfferMapper()
            ->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, new ProductOfferTransfer());
    }
}
