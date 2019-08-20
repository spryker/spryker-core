<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CmsContentWidgetProductConnectorConfig extends AbstractBundleConfig
{
    public const SHOW_NOT_AVAILABLE_PRODUCTS = false;

    /**
     * @return bool
     */
    public function getShowNotAvailableProducts(): bool
    {
        return static::SHOW_NOT_AVAILABLE_PRODUCTS;
    }
}
