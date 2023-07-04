<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferShipmentTypeConfig extends AbstractBundleConfig
{
    /**
     * @phpstan-var positive-int
     *
     * @var int
     */
    protected const PRODUCT_OFFER_SHIPMENT_TYPE_READ_BATCH_SIZE = 500;

    /**
     * Specification:
     * - Returns the number of `ProductOfferShipmentType` entities in the batch to be read.
     *
     * @api
     *
     * @return int<1, max>
     */
    public function getProductOfferShipmentTypeReadBatchSize(): int
    {
        return static::PRODUCT_OFFER_SHIPMENT_TYPE_READ_BATCH_SIZE;
    }
}
