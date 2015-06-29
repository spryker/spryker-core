<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerEngine\Zed\Translation\Business\Exception\TranslationFormatNotFoundException;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;

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