<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;

/**
 * Provides additional validation rules.
 */
interface PriceProductOfferValidatorPluginInterface
{
    /**
     * Specification:
     * - Provides additional validation by a given collection of PriceProductOfferTransfer.
     * - Returns validation response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): ValidationResponseTransfer;
}
