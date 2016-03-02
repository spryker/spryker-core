<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class LocaleConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getLocaleFile()
    {
        return realpath(__DIR__ . '/Business/Internal/Install/locales.txt');
    }

}
