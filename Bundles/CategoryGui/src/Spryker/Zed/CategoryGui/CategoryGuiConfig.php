<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CategoryGuiConfig extends AbstractBundleConfig
{
    protected const REDIRECT_URL_CATEGORY_GUI = '/category-gui/list';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultRedirectUrl(): string
    {
        return static::REDIRECT_URL_CATEGORY_GUI;
    }
}
