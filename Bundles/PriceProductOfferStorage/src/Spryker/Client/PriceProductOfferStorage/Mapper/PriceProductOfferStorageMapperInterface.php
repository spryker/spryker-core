<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Mapper;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductOfferStorageMapperInterface
{
    /**
     * @param array $priceProductOffer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapPriceProductOfferStorageDataToPriceProductTransfer(array $priceProductOffer, PriceProductTransfer $priceProductTransfer): PriceProductTransfer;
}
