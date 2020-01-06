<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;

interface PriceProductOfferMapperInterface
{
    /**
     * @param \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer $priceProductOfferEntity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapPriceProductOfferEntityToPriceProductTransfer(SpyPriceProductOffer $priceProductOfferEntity, PriceProductTransfer $priceProductTransfer): PriceProductTransfer;
}
