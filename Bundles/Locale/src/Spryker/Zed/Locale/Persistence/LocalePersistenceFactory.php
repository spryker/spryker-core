<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
