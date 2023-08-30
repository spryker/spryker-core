<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;

interface ProductOfferShipmentTypeReaderInterface
{
    /**
     * @param list<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOfferShipmentTypeCollectionByProductOfferIds(array $productOfferIds): ProductOfferShipmentTypeCollectionTransfer;
}
