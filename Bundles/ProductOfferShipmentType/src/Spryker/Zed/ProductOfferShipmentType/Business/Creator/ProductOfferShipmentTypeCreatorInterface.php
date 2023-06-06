<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Creator;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferShipmentTypeCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;
}
