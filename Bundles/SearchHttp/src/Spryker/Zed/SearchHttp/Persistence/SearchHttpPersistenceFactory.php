<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Persistence;

use Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfigQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SearchHttp\Persistence\Mapper\SearchHttpConfigMapper;

/**
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpRepositoryInterface getRepository()
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SearchHttp\SearchHttpConfig getConfig()
 */
class SearchHttpPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfigQuery
     */
    public function createSearchHttpPropelQuery(): SpySearchHttpConfigQuery
    {
        return SpySearchHttpConfigQuery::create();
    }

    /**
     * @return \Spryker\Zed\SearchHttp\Persistence\Mapper\SearchHttpConfigMapper
     */
    public function createSearchHttpConfigMapper(): SearchHttpConfigMapper
    {
        return new SearchHttpConfigMapper();
    }
}
