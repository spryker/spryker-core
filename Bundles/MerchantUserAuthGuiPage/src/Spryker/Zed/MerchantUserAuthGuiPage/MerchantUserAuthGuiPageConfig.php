<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserAuthGuiPage;

use Spryker\Shared\MerchantUserAuthGuiPage\MerchantUserAuthGuiPageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantUserAuthGuiPageConfig extends AbstractBundleConfig
{
    public const MERCHANT_USER_DEFAULT_URL_REDIRECT = '/';

    /**
     * @return string
     */
    public function getDefaultUrlRedirect(): string
    {
        return $this->get(
            MerchantUserAuthGuiPageConstants::MERCHANT_USER_DEFAULT_URL_REDIRECT,
            static::MERCHANT_USER_DEFAULT_URL_REDIRECT
        );
    }
}
