<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferPersistenceFactory getFactory()
 */
class PriceProductOfferRepository extends AbstractRepository implements PriceProductOfferRepositoryInterface
{
    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductOfferTransfers(array $skus): array
    {
        $priceProductOfferEntities = $this->getFactory()
            ->getPriceProductOfferPropelQuery()
            ->joinWithSpyProductOffer()
            ->useSpyProductOfferQuery()
                ->filterByConcreteSku_In($skus)
            ->endUse()
            ->joinWithSpyPriceType()
            ->joinWithSpyCurrency()
            ->find();

        $priceProductTransfers = [];

        foreach ($priceProductOfferEntities as $priceProductOfferEntity) {
            $priceProductTransfers[] = $this->getFactory()
                ->createPriceProductOfferMapper()
                ->mapPriceProductOfferEntityToPriceProductTransfer($priceProductOfferEntity, new PriceProductTransfer());
        }

        return $priceProductTransfers;
    }
}
