<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AvailabilityConfig extends AbstractBundleConfig
{
    public const ERROR_CODE_PRODUCT_UNAVAILABLE = 4002;
    public const RESOURCE_TYPE_AVAILABILITY_ABSTRACT = 'availability_abstract';

    protected const ERROR_TYPE_AVAILABILITY = 'Availability';
    protected const PARAMETER_PRODUCT_SKU_AVAILABILITY = '%sku%';

    /**
     * @return string
     */
    public function getProductUnavailableErrorCode()
    {
        return static::ERROR_CODE_PRODUCT_UNAVAILABLE;
    }

    /**
     * @return string
     */
    public function getAvailabilityErrorType(): string
    {
        return static::ERROR_TYPE_AVAILABILITY;
    }

    /**
     * @return string
     */
    public function getAvailabilityProductSkuParameter(): string
    {
        return static::PARAMETER_PRODUCT_SKU_AVAILABILITY;
    }
}
