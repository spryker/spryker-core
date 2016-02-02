<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;

class LocaleQueryContainer extends AbstractQueryContainer implements LocaleQueryContainerInterface
{

    /**
     * @param string $localeName
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocaleByName($localeName)
    {
        $query = SpyLocaleQuery::create();
        $query
            ->filterByLocaleName($localeName);

        return $query;
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocales()
    {
        $query = SpyLocaleQuery::create();

        return $query;
    }

}
