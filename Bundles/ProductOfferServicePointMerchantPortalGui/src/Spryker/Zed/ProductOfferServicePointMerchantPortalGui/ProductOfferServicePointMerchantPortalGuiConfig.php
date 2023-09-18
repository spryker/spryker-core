<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferServicePointMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines max amount of choices displayed for service point select field.
     *
     * @api
     *
     * @return int|null
     */
    public function getServicePointChoicesLimit(): ?int
    {
        return null;
    }
}
