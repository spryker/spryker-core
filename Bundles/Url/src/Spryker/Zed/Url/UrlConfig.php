<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url;

use Spryker\Shared\Url\UrlConfig as SharedUrlConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class UrlConfig extends AbstractBundleConfig
{
    public const RESOURCE_TYPE_URL = SharedUrlConfig::RESOURCE_TYPE_URL;

    /**
     * @var string
     */
    public const RESOURCE_TYPE_REDIRECT = 'redirect';
}
