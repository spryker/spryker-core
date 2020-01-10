<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CmsContentWidgetProductConnectorConfig extends AbstractBundleConfig
{
    protected const IS_UNAVAILABLE_PRODUCTS_DISPLAYED = false;

    /**
     * @return bool
     */
    public function getIsUnavailableProductsDisplayed(): bool
    {
        return static::IS_UNAVAILABLE_PRODUCTS_DISPLAYED;
    }
}
