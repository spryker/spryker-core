<?php

namespace SprykerEngine\Zed\Translation;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerEngine\Zed\Translation\Business\Exception\TranslationFormatNotFoundException;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;

class TranslationConfig extends AbstractBundleConfig
{
    /**
     * @param string $format
     *
     * @return LoaderInterface
     *
     * @throws TranslationFormatNotFoundException
     */
    public static function getLoader($format)
    {
        switch($format) {
            case 'po':
                return new PoFileLoader();
            case 'csv':
                return new CsvFileLoader();
            default:
                throw new TranslationFormatNotFoundException(
                    sprintf(
                        'There is no loader for the format "%s".',
                        $format
                    )
                );
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