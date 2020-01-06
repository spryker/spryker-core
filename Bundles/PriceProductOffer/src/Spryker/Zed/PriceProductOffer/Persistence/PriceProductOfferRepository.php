<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferPersistenceFactory getFactory()
 */
class PriceProductOfferRepository extends AbstractRepository implements PriceProductOfferRepositoryInterface
{
    /**
     * @param string[] $skus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductTransfers(array $skus, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        $priceProductOfferEntities = $this->getFactory()
            ->getPriceProductOfferPropelQuery()
            ->joinWithSpyProductOffer()
            ->useSpyProductOfferQuery()
                ->filterByConcreteSku_In($skus)
            ->endUse()
            ->joinWithSpyPriceType()
            ->joinWithSpyCurrency()
            ->filterByFkCurrency($priceProductCriteriaTransfer->getIdCurrency())
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
