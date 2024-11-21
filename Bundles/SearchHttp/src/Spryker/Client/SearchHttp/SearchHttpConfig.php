<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp;

use Spryker\Client\Kernel\AbstractBundleConfig;

class SearchHttpConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getForwardForAddress(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
    }
}
