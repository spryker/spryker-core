<?php

namespace SprykerEngine\Zed\Translation;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;

class TranslationConfig extends AbstractBundleConfig
{
    public static function getLoader($format)
    {
        switch($format) {
            case 'po':
                return new PoFileLoader();
            case 'csv':
                return new CsvFileLoader();
            default:
                // todo: create Exception
                throw new \Exception('Unknown Format');
        }
    }

    /**
     * @return string[]
     */
    public static function getPathPatterns()
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Zed/*/Translation/*.*',
            APPLICATION_SOURCE_DIR . '/*/Zed/*/Translation/*.*',
        ];
    }
}