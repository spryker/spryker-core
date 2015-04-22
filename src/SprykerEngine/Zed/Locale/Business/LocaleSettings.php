<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Zed\Locale\Business;

class LocaleSettings
{
    /**
     * @return string
     */
    public function getLocaleFile()
    {
        return realpath(__DIR__ . '/Internal/Install/locales.txt');
    }
}
