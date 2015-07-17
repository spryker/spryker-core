<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class TranslationConfig extends AbstractBundleConfig
{

    /**
     * @return string[]
     */
    public function getPathPatterns()
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Zed/*/Translation/*.*',
            APPLICATION_SOURCE_DIR . '/*/Zed/*/Translation/*.*',
        ];
    }

}
