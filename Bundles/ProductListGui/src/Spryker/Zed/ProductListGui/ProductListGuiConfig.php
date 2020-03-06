<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductListGuiConfig extends AbstractBundleConfig
{
    protected const REDIRECT_URL_DEFAULT = '/product-list-gui';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultRedirectUrl(): string
    {
        return static::REDIRECT_URL_DEFAULT;
    }
}
