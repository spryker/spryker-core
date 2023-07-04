<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Indexer;

use ArrayObject;

class ProductOfferIndexer implements ProductOfferIndexerInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function getProductOfferTransfersIndexedByIdProductOffer(ArrayObject $productOfferTransfers): array
    {
        $indexedProductOfferTransfers = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            $indexedProductOfferTransfers[$productOfferTransfer->getIdProductOfferOrFail()] = $productOfferTransfer;
        }

        return $indexedProductOfferTransfers;
    }
}
