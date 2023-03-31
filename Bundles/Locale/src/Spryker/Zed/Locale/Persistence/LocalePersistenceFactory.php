<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Locale\Persistence\SpyLocaleStore;
use Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Locale\LocaleDependencyProvider;
use Spryker\Zed\Locale\Persistence\Propel\Mapper\LocaleMapper;

/**
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface getRepository()
 * @method \Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface getEntityManager()
 */
class LocalePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery<mixed>
     */
    public function createLocalePropelQuery(): SpyLocaleQuery
    {
        return SpyLocaleQuery::create();
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery<mixed>
     */
    public function createLocaleStorePropelQuery(): SpyLocaleStoreQuery
    {
        return SpyLocaleStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::PROPEL_QUERY_STORE);
    }

    /**
     * @return \Spryker\Zed\Locale\Persistence\Propel\Mapper\LocaleMapper
     */
    public function createLocaleMapper(): LocaleMapper
    {
        return new LocaleMapper();
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocale
     */
    public function createLocaleEntity(): SpyLocale
    {
        return new SpyLocale();
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleStore
     */
    public function createLocaleStoreEntity(): SpyLocaleStore
    {
        return new SpyLocaleStore();
    }
}
