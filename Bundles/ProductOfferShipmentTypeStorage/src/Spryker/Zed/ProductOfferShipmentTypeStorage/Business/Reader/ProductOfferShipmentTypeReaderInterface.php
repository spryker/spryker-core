<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader;

interface ProductOfferShipmentTypeReaderInterface
{
    /**
     * @param list<int> $productOfferIds
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypeIteratorByProductOfferIds(
        array $productOfferIds
    ): iterable;

    /**
     * @param list<int> $productOfferShipmentTypeIds
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypeIteratorByProductOfferShipmentTypeIds(
        array $productOfferShipmentTypeIds
    ): iterable;

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypeIteratorByShipmentTypeIds(array $shipmentTypeIds): iterable;
}
