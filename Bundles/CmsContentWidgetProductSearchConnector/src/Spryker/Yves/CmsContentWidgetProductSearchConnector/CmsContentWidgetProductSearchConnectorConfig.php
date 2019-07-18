<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CmsContentWidgetProductSearchConnectorConfig extends AbstractBundleConfig
{
    public const SEARCH_LIMIT = 8;

    /**
     * @return int
     */
    public function getSearchLimit()
    {
        return static::SEARCH_LIMIT;
    }
}
