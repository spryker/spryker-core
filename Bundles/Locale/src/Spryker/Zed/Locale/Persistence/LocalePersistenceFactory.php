<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Persistence;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Persistence\LocaleQueryContainer getQueryContainer()
 */
class LocalePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function createLocaleQuery()
    {
        return SpyLocaleQuery::create();
    }

}
