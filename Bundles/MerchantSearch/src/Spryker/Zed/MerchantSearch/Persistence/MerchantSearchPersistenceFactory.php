<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Persistence;

use Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\MerchantSearch\MerchantSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchRepositoryInterface getRepository()
 */
class MerchantSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearchQuery
     */
    public function getMerchantSearchPropelQuery(): SpyMerchantSearchQuery
    {
        return SpyMerchantSearchQuery::create();
    }
}
