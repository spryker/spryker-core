<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Persistence;

interface ProductOfferShipmentTypeEntityManagerInterface
{
    /**
     * @param string $productOfferReference
     * @param string $shipmentTypeUuid
     *
     * @return void
     */
    public function createProductOfferShipmentType(string $productOfferReference, string $shipmentTypeUuid): void;

    /**
     * @param string $productOfferReference
     * @param array<string> $shipmentTypeUuids
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypes(string $productOfferReference, array $shipmentTypeUuids): void;
}
