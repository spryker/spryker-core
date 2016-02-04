<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method LocalePersistenceFactory getFactory()
 */
class LocaleQueryContainer extends AbstractQueryContainer implements LocaleQueryContainerInterface
{

    /**
     * @param string $localeName
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocaleByName($localeName)
    {
        $query = $this->getFactory()->createLocaleQuery()
            ->filterByLocaleName($localeName);

        return $query;
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocales()
    {
        $query = $this->getFactory()->createLocaleQuery();

        return $query;
    }

}
