<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Validator;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer;

interface ProductOfferValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer
     */
    public function validate(
        ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
    ): ProductOfferShipmentTypeCollectionResponseTransfer;
}
