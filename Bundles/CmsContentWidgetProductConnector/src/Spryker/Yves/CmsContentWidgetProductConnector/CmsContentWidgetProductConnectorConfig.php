<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CmsContentWidgetProductConnectorConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isUnavailableProductsDisplayed(): bool
    {
        return false;
    }
}
