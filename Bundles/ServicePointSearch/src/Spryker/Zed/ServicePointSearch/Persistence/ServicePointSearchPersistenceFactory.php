<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Persistence;

use Orm\Zed\ServicePointSearch\Persistence\SpyServicePointSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ServicePointSearch\ServicePointSearchConfig getConfig()
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchRepositoryInterface getRepository()
 */
class ServicePointSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ServicePointSearch\Persistence\SpyServicePointSearchQuery
     */
    public function getServicePointSearchPropelQuery(): SpyServicePointSearchQuery
    {
        return SpyServicePointSearchQuery::create();
    }
}
