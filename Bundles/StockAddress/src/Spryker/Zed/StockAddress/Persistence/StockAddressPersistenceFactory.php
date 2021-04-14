<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Persistence;

use Orm\Zed\StockAddress\Persistence\SpyStockAddressQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\StockAddress\Persistence\Propel\Mapper\StockAddressMapper;

/**
 * @method \Spryker\Zed\StockAddress\StockAddressConfig getConfig()
 * @method \Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface getRepository()
 * @method \Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface getEntityManager()
 */
class StockAddressPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\StockAddress\Persistence\SpyStockAddressQuery
     */
    public function createStockAddressQuery(): SpyStockAddressQuery
    {
        return SpyStockAddressQuery::create();
    }

    /**
     * @return \Spryker\Zed\StockAddress\Persistence\Propel\Mapper\StockAddressMapper
     */
    public function createStockAddressMapper(): StockAddressMapper
    {
        return new StockAddressMapper();
    }
}
