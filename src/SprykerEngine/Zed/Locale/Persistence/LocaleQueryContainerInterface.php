<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Zed\Locale\Persistence;

use SprykerEngine\Zed\Locale\Persistence\Propel\SpyLocaleQuery;

interface LocaleQueryContainerInterface
{
    /**
     * @param string $localeName
     *
     * @return SpyLocaleQuery
     */
    public function queryLocaleByName($localeName);

    /**
     * @return SpyLocaleQuery
     */
    public function queryLocales();
}
