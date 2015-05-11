<?php

namespace SprykerEngine\Zed\Translation;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;

class TranslationConfig extends AbstractBundleConfig
{
    public static function getFormats()
    {
        return array_keys(self::getLoaders());
    }

    /**
     * @return LoaderInterface[]
     */
    public static function getLoaders()
    {
        return [
            'po'  => new PoFileLoader(),
            'csv' => new CsvFileLoader(),
        ];
    }
}