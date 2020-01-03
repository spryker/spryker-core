<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 */
class CurrencyPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function createCurrencyQuery()
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
     * @return \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    protected function getInternationalization()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTERNATIONALIZATION);
    }
}
