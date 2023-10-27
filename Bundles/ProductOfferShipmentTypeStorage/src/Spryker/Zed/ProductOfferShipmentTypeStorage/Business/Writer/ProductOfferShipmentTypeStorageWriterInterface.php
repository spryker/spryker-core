<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Writer;

use ArrayObject;

interface ProductOfferShipmentTypeStorageWriterInterface
{
    /**
     * @param list<int> $productOfferIds
     *
     * @return void
     */
    public function writeProductOfferShipmentTypeStorageCollectionByProductOfferIds(array $productOfferIds): void;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer> $productOfferShipmentTypeTransfers
     *
     * @return void
     */
    public function writeProductOfferShipmentTypeStorageCollection(ArrayObject $productOfferShipmentTypeTransfers): void;
}
