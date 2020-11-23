<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer;

interface PriceProductOfferValidatorInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer
     */
    public function validateProductOfferPrices(
        ArrayObject $priceProductTransfers
    ): PriceProductOfferCollectionValidationResponseTransfer;
}
