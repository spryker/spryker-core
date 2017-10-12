<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AvailabilityConfig extends AbstractBundleConfig
{
    const ERROR_CODE_PRODUCT_UNAVAILABLE = 4002;
    const RESOURCE_TYPE_AVAILABILITY_ABSTRACT = 'availability_abstract';

    /**
     * @return string
     */
    public function getProductUnavailableErrorCode()
    {
        return static::ERROR_CODE_PRODUCT_UNAVAILABLE;
    }
}
