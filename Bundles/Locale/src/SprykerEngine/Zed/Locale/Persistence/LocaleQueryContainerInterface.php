<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Locale\Persistence;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;

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
