<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business\Validator;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface PriceProductOfferVolumeValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
    ): ValidationResponseTransfer;
}
