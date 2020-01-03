<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Locale\Persistence\Propel\Mapper\LocaleMapper;

/**
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface getRepository()
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

    /**
     * @return \Spryker\Zed\Locale\Persistence\Propel\Mapper\LocaleMapper
     */
    public function createLocaleMapper(): LocaleMapper
    {
        return new LocaleMapper();
    }
}
