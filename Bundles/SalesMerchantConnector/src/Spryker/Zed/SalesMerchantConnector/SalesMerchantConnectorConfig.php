<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesMerchantConnectorConfig extends AbstractBundleConfig
{
    /**
     * Sets pattern in which merchant order reference is being generated
     */
    protected const MERCHANT_ORDER_REFERENCE_PATTERN = '%s/%s';

    /**
     * @return string
     */
    public function getMerchantOrderReferencePattern(): string
    {
        return static::MERCHANT_ORDER_REFERENCE_PATTERN;
    }
}
