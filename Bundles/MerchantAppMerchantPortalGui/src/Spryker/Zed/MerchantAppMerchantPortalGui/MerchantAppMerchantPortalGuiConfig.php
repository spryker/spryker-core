<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAppMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantAppMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\MerchantPortalApplication\MerchantPortalConstants::BASE_URL_MP
     *
     * @var string
     */
    protected const MERCHANT_PORTAL_BASE_URL = 'MERCHANT_PORTAL_APPLICATION:BASE_URL_MP';

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantPortalBaseUrl(): string
    {
        return $this->get(static::MERCHANT_PORTAL_BASE_URL);
    }
}
