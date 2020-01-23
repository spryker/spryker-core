<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferPersistenceFactory getFactory()
 */
class PriceProductOfferRepository extends AbstractRepository implements PriceProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildPriceProductOfferDimensionQueryCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?QueryCriteriaTransfer
    {
        return $this->getFactory()->createPriceProductOfferQueryExpander()->buildPriceProductOfferDimensionQueryCriteria($priceProductCriteriaTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildUnconditionalPriceProductOfferDimensionQueryCriteria(): QueryCriteriaTransfer
    {
        return $this->getFactory()->createPriceProductOfferQueryExpander()->buildUnconditionalPriceProductOfferDimensionQueryCriteria();
    }
}
