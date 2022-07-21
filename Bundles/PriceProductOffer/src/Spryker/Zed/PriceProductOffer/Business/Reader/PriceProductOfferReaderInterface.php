<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;

interface PriceProductOfferReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductOfferPrices(
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): ArrayObject;
}
