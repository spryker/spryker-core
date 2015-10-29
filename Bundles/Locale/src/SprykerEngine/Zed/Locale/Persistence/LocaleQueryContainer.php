<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Locale\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;

class LocaleQueryContainer extends AbstractQueryContainer implements LocaleQueryContainerInterface
{

    /**
     * @param string $localeName
     *
     * @return SpyLocaleQuery
     */
    public function queryLocaleByName($localeName)
    {
        $query = SpyLocaleQuery::create();
        $query
            ->filterByLocaleName($localeName)
        ;

        return $query;
    }

    /**
     * @return SpyLocaleQuery
     */
    public function queryLocales()
    {
        $query = SpyLocaleQuery::create();

        return $query;
    }

}
