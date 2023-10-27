<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Saver;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer;

interface ProductOfferShipmentTypeSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\ProductOfferShipmentType\Business\Exception\ProductOfferValidationException
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer
     */
    public function saveProductOfferShipmentTypes(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): ProductOfferShipmentTypeCollectionResponseTransfer;
}
