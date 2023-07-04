<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage\Generator;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

interface ProductOfferShipmentTypeKeyGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param string $storeName
     *
     * @return string
     */
    public function generateProductOfferShipmentTypeKey(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        string $storeName
    ): string;
}
