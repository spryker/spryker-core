<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Locale\Persistence\LocalePersistenceFactory getFactory()
 */
class LocaleQueryContainer extends AbstractQueryContainer implements LocaleQueryContainerInterface
{

    /**
     * @api
     *
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
     * @api
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocales()
    {
        $query = $this->getFactory()->createLocaleQuery();

        return $query;
    }

}
