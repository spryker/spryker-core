<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface;
use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyEntityManagerInterface getEntityManager()
 */
class CurrencyPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery<mixed>
     */
    public function createCurrencyPropelQuery(): SpyCurrencyQuery
    {
        return SpyCurrencyQuery::create();
    }

    /**
     * @return \Spryker\Zed\Currency\Persistence\CurrencyMapper
     */
    public function createCurrencyMapper(): CurrencyMapper
    {
        return new CurrencyMapper($this->getInternationalization());
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery<mixed>
     */
    public function createCurrencyStorePropelQuery(): SpyCurrencyStoreQuery
    {
        return SpyCurrencyStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::PROPEL_QUERY_STORE);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    protected function getInternationalization(): CurrencyToInternationalizationInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTERNATIONALIZATION);
    }
}
