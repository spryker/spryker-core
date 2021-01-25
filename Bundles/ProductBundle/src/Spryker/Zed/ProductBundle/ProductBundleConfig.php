<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductBundleConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\Availability\AvailabilityConfig::ERROR_TYPE_AVAILABILITY
     */
    protected const ERROR_TYPE_AVAILABILITY = 'Availability';

    /**
     * @see \Spryker\Zed\Availability\AvailabilityConfig::PARAMETER_PRODUCT_SKU_AVAILABILITY
     */
    protected const PARAMETER_PRODUCT_SKU_AVAILABILITY = '%sku%';

    /**
     * @api
     *
     * @return string
     */
    public function getAvailabilityErrorType(): string
    {
        return static::ERROR_TYPE_AVAILABILITY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAvailabilityProductSkuParameter(): string
    {
        return static::PARAMETER_PRODUCT_SKU_AVAILABILITY;
    }
}
