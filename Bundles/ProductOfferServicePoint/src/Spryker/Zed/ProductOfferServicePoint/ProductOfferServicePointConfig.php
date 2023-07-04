<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferServicePointConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const PRODUCT_OFFER_SERVICES_PROCESS_BATCH_SIZE = 1000;

    /**
     * @api
     *
     * @return int
     */
    public function getProductOfferServicesProcessBatchSize(): int
    {
        return static::PRODUCT_OFFER_SERVICES_PROCESS_BATCH_SIZE;
    }
}
