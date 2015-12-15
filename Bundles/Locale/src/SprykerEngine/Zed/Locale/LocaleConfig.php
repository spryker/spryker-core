<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
