<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface getRepository()
 */
class MerchantStockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock
     */
    public function createMerchantStockEntity(): SpyMerchantStock
    {
        return new SpyMerchantStock();
    }

    /**
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery
     */
    public function createMerchantStockQuery(): SpyMerchantStockQuery
    {
        return SpyMerchantStockQuery::create();
    }
}
