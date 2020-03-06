<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantProductOfferSearchConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return null;
    }
}
