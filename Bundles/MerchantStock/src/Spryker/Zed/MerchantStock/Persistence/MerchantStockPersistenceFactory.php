<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantStock\Persistence\Mapper\MerchantStockMapper;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantStock\MerchantStockConfig getConfig()
 */
class MerchantStockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery
     */
    public function createMerchantStockPropelQuery(): SpyMerchantStockQuery
    {
        return SpyMerchantStockQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantStock\Persistence\Mapper\MerchantStockMapper
     */
    public function createMerchantStockMapper(): MerchantStockMapper
    {
        return new MerchantStockMapper();
    }
}
